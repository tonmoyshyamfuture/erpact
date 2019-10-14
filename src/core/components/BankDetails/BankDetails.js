import React, { Component, Suspense } from 'react';
import { Link } from "react-router-dom";
import { Button, Modal } from 'react-bootstrap';
import Select from 'react-select';
import * as $ from 'jquery';
import * as transactionService from '../../services/TransactionService';
const customStyles = {
    control: styles => ({ ...styles, backgroundColor: 'white', width: '100%', })
};



class BankDetails extends Component {
    constructor(props, context) {
        super(props, context);

        this.handleHide = this.handleHide.bind(this);

        this.state = {
            show: true,
        };
    }

    componentDidMount = () => {
        this.buildRefKey()
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

            if(this.refs[field.name].name == "btn"){
                this.handleHide();                
            }
        }
    }

    bankOnChange = (selectedOption) => {
        this.props.getBankOnChange(selectedOption)
        this.refs.btn.focus()    
    }

    handleHide() {
        this.setState({ show: false });
        this.props.getBankDetailsModalKey(false)
        // setTimeout(function(){ $(".wqothers").focus(); }, 500);
    }

    render() {
        console.log("props=>",this.props);
        return (
            <React.Fragment>                
                <div className="modal-container" >
                    <Modal
                    show={this.state.show}
                    onHide={this.handleHide}
                    container={this}
                    aria-labelledby="contained-modal-title"
                    >
                    <Modal.Header closeButton>
                        <Modal.Title id="contained-modal-title">
                        Banks
                        </Modal.Title>
                    </Modal.Header>
                    <Modal.Body>
                        <div className="form-group">
                            <Select
                                openMenuOnFocus={true}
                                autoFocus={true}
                                //value={this.props.bankValue}
                                value={ {label: this.props.wqbankName, value: this.props.bankValue} }
                                onChange={this.bankOnChange.bind(this)}
                                options={this.props.allBankList} 
                                styles={customStyles}
                                placeholder={`Select Bank`}
                                ref="bank" name="bank"
                            />
                        </div>
                    </Modal.Body>
                    <Modal.Footer>
                        <button onClick={this.handleHide} className="btn btn-danger" name="btn" ref="btn">Close</button>
                    </Modal.Footer>
                    </Modal>
                </div>
            </React.Fragment>
        );
    }
}
  
export default BankDetails;