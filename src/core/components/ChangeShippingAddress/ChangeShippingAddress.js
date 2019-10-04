import React, { Component, Suspense } from 'react';
import { Link } from "react-router-dom";
import { Button, Modal } from 'react-bootstrap';
import Select from 'react-select';
import * as $ from 'jquery';
import * as transactionService from '../../services/TransactionService';
const customStyles = {
    control: styles => ({ ...styles, backgroundColor: 'white', width: '100%', })
};



class ChangeShippingAddress extends Component {
    constructor(props, context) {
        super(props, context);

        this.handleHide = this.handleHide.bind(this);

        this.state = {
            show: true,
            shippingList: [],
        };
    }

    componentDidMount = () => {
        this.buildRefKey()
        if(this.props.shippingValue != null){
            this.getShippingAddressByContactId(this.props.shippingValue['value'])
        }
    }

    componentDidUpdate = (prevProps) => {
        this.buildRefKey()
    }

    buildRefKey = () => {
        for (let x in this.refs) {
            this.refs[x].onkeypress = (e) => 
              this._handleKeyPress(e, this.refs[x]);
        }
    }
    
    _handleKeyPress(e, field) {
    
        if (e.keyCode === 13) {
            e.preventDefault();
            let arr = [];
            
            for (let x in this.refs) {        
                if(this.refs.hasOwnProperty(x)){
                    arr.push(x);
                }        
            }
            
            for(var i = 0 ; i< arr.length - 1; i++){
                if(this.refs[field.name].name === arr[i]){                    
                    this.refs[arr[i+1]].focus()
                }
            }
        }
    }

    contactOnChange = (selectedOption) => {
        this.props.getContactOnChange(selectedOption)
        this.getShippingAddressByContactId(selectedOption['value'])
        this.props.getShippingOnChange(null)
        this.props.getShippingAndLedgerState(null)    
    }

    shippingOnChange = (selectedOption) => {
        this.props.getShippingOnChange(selectedOption)
        this.props.getShippingAndLedgerState(selectedOption['data'])  
    }

    handleHide() {
        this.setState({ show: false });
        this.props.getChangeShippingAddressModalKey(false)
        // setTimeout(function(){ $(".wqothers").focus(); }, 500);
    }

    getShippingAddressByContactId(id){
        transactionService.getShippingAddressByContactId(id).then(res => {
            console.log(res.data)
            var shippingData = [];
            res.data.forEach(x => {
                var d = {
                    value: x.id,
                    label: x.address,
                    data: x
                }
                shippingData.push(d)
            })            
            this.setState({
                shippingList: shippingData,
            }, function() {
                this.refs.shipping.focus();
            })
            
        })
    }

    render() {
        console.log("props=>",this.props);
        return (
            <React.Fragment>                
                <div className="modal-container">
                    <Modal
                    show={this.state.show}
                    onHide={this.handleHide}
                    container={this}
                    aria-labelledby="contained-modal-title"
                    >
                    <Modal.Header closeButton>
                        <Modal.Title id="contained-modal-title">
                        Shipping Options
                        </Modal.Title>
                    </Modal.Header>
                    <Modal.Body>
                        <div className="row">
                            <div className="col-md-6">
                                <div className="form-group">
                                    <label>Contact</label>
                                    <Select
                                        autoFocus
                                        value={this.props.contactValue}
                                        onChange={this.contactOnChange.bind(this)}
                                        options={this.props.allContactList} 
                                        styles={customStyles}
                                        placeholder={`Select Contact`}
                                        ref="contact" name="contact"
                                    />
                                </div>                                
                            </div>
                            <div className="col-md-6">
                                <div className="form-group">
                                    <label>Shipping Address</label>
                                    <Select
                                        value={this.props.shippingValue}
                                        onChange={this.shippingOnChange.bind(this)}
                                        options={this.state.shippingList} 
                                        styles={customStyles}
                                        placeholder={`Select Shipping`}
                                        ref="shipping" name="shipping"
                                    />
                                </div>                                
                            </div>
                        </div>
                        
                    </Modal.Body>
                    <Modal.Footer>
                        <Button onClick={this.handleHide} className="btn btn-danger">Close</Button>
                    </Modal.Footer>
                    </Modal>
                </div>
            </React.Fragment>
        );
    }
}
  
export default ChangeShippingAddress;