import React, { Component, Suspense } from 'react';
import { Link } from "react-router-dom";
import { Button, Modal } from 'react-bootstrap';
import * as transactionService from '../../services/TransactionService';
import Autocomplete from 'react-autocomplete';
import Select from 'react-select';
import { toast } from 'react-toastify';
const customLedgerStyles = {
    control: styles => ({ ...styles, backgroundColor: 'white', width: '100%', })
};

class Currency extends Component {
    constructor(props, context) {
        super(props, context);

        this.handleHide = this.handleHide.bind(this);

        this.state = {
            show: true
        };
    }

    componentDidMount = () => {
        this.buildRefKey()     
    }
    
    componentDidUpdate = (prevProps) => {
        this.buildRefKey()
    }

    

    unitPriceOnChange = (event) => {
        const re = RegExp(/^[0-9]+(\.[0-9][0-9]?)*$/);
        if (event.target.value === '' || re.test(event.target.value)) {
            let values = {...this.props.selectedCurrencyObj};        
            values['unit_price'] = +event.target.value;
            this.props.changeCurrencyValue(values)
        }       
        
    }

    

    handleHide() {       
        
        this.setState({ show: false });
        this.props.getCurrencyModalKey(false)
        
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

    _unitPriceKeyPress = (e) => {      
        if (e.key === 'Enter') {
            this.handleHide();            
        }        
    }

    

    render() {        
        return (
            <React.Fragment>                
                <div className="modal-container" >
                    <Modal
                    show={this.state.show}
                    onHide={this.handleHide}
                    container={this}
                    bsSize="small"
                    aria-labelledby="contained-modal-title-sm"
                    >
                    <Modal.Header closeButton>
                        <Modal.Title id="contained-modal-title-sm">
                            {this.props.selectedCurrencyObj.currency_name}
                        </Modal.Title>
                    </Modal.Header>
                    <Modal.Body>
                        <div className="row">
                            <div className="col-md-6">
                                <label>Unit Price</label>
                            </div>
                            <div className="col-md-6">
                                <input type="text" ref="unit_price" name="unit_price" value={this.props.selectedCurrencyObj.unit_price} min="0" className="form-control" placeholder="Unit Price" autoComplete="off" onChange={this.unitPriceOnChange.bind(this)} onKeyPress={this._unitPriceKeyPress.bind(this)}></input>
                            </div>
                        </div>
                    </Modal.Body>
                    {/* <Modal.Footer>
                        <Button onClick={this.handleHide}>Save</Button>
                    </Modal.Footer> */}
                    </Modal>
                </div>
            </React.Fragment>
        );
    }
}
  
export default Currency;