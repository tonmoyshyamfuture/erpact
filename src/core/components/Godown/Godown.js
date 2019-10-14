import React, { Component, Suspense } from 'react';
import { Link } from "react-router-dom";
import { Button, Modal } from 'react-bootstrap';
import * as transactionService from '../../services/TransactionService';
import Autocomplete from 'react-autocomplete';
import Select from 'react-select';
// import * as $ from 'jquery';
import { toast } from 'react-toastify';
const customLedgerStyles = {
    control: styles => ({ ...styles, backgroundColor: 'white', width: '100%', })
};

class Godown extends Component {
    constructor(props, context) {
        super(props, context);

        this.handleHide = this.handleHide.bind(this);

        this.state = {
            show: true,
            allBatchList: [],
            goModal: true
        };
    }

    componentDidMount = () => {
        this.buildRefKey()
        let values = [...this.props.selectedProductList];
        for(var i = 0 ; i< values[this.props.productIndex]['productGodownBatchData'].length ; i++){
            values[this.props.productIndex]['productGodownBatchData'][i]['allGodownList'] = values[this.props.productIndex]['ProductAllGodownList']
        }
        this.setState({values})
        
        setTimeout(() => {
            if(this.state.goModal) {
                this.refs.godownValue0.focus();
            }
        }, 0);
        
    }

    componentDidUpdate = (prevProps) => {
        this.buildRefKey()        
    }
    

    quantityOnChange = (i,event) => {
        console.log(event.target.value)
        // const re = RegExp(/^[0-9]+(\.[0-9][0-9]?)*$/);
        // if (event.target.value === '' || re.test(event.target.value)) {
        // if (event.target.value != '') {
            let values = [...this.props.selectedProductList];
            if(values[this.props.productIndex]['productGodownBatchData'][i]['godownValue'] == ''){
                toast.error(`Please Select Godown`, {
                    position: "top-right",
                    autoClose: 1000,
                    hideProgressBar: false,
                    closeOnClick: true,
                    pauseOnHover: true,
                    draggable: true
                });
            }
            else{
                var qty_val = event.target.value
                var available_stock = values[this.props.productIndex]['stock'] - (values[this.props.productIndex]['qty'] + (qty_val - values[this.props.productIndex]['productGodownBatchData'][i]['qty']));
                if(event.target.value === ''){
                    values[this.props.productIndex]['productGodownBatchData'][i]['qty'] = event.target.value
                }
                else if(available_stock >= 0){
                    values[this.props.productIndex]['productGodownBatchData'][i]['qty'] = qty_val
                }
                else {                    
                    if(qty_val + available_stock > 0){
                        values[this.props.productIndex]['productGodownBatchData'][i]['qty'] = qty_val + available_stock
                    }
                    else{
                        toast.error(`Stock is not available`, {
                            position: "top-right",
                            autoClose: 1000,
                            hideProgressBar: false,
                            closeOnClick: true,
                            pauseOnHover: true,
                            draggable: true
                        });
                    }
                    
                }
                this.setState({values}, () => {
                    this.calculateGross(i)
                })
                // this.refs['rate'+i].focus(); 
            }
        // }        
        
    }

    calculateGross = (i) => {
        let values = [...this.props.selectedProductList];
        var qty = values[this.props.productIndex]['productGodownBatchData'][i]['qty']
        var rate = values[this.props.productIndex]['productGodownBatchData'][i]['rate']
        var grossTotal = 0;
        grossTotal = qty*rate;
        values[this.props.productIndex]['productGodownBatchData'][i]['grossTotal'] = parseFloat(grossTotal.toFixed(2));
        this.setState({values}, () => {
            this.props.calculateAvgQtyRate(this.props.productIndex)
        })
    }



    rateOnChange = (i,event) => {
        // const re = RegExp(/^[0-9]+(\.[0-9][0-9]?)*$/);
        // if (event.target.value === '' || re.test(event.target.value)) {
        // if (event.target.value != '') {
            let values = [...this.props.selectedProductList];
            if(values[this.props.productIndex]['productGodownBatchData'][i]['godownValue'] == ''){
                toast.error(`Please Select Godown`, {
                    position: "top-right",
                    autoClose: 1000,
                    hideProgressBar: false,
                    closeOnClick: true,
                    pauseOnHover: true,
                    draggable: true
                });
            }
            else {
                if(event.target.value === ''){
                    values[this.props.productIndex]['productGodownBatchData'][i]['rate'] = event.target.value
                }
                else{
                    values[this.props.productIndex]['productGodownBatchData'][i]['rate'] = event.target.value
                }
                this.setState({values}, () => {
                    this.calculateGross(i)
                })
                 
            } 
        // }
         
    }

    handleHide() {
        let values = [...this.props.selectedProductList];
        var productGodownBatchDataList = [];
        
        if(values[this.props.productIndex]['productGodownBatchData'].length > 1){
            for(var i = 0 ; i< values[this.props.productIndex]['productGodownBatchData'].length ; i++){
                if(values[this.props.productIndex]['productGodownBatchData'][i]['qty'] > 0 ){
                    productGodownBatchDataList.push(values[this.props.productIndex]['productGodownBatchData'][i])                                    
                }                
                if(i == values[this.props.productIndex]['productGodownBatchData'].length - 1){
                    values[this.props.productIndex]['productGodownBatchData'] = productGodownBatchDataList
                    this.setState({ values }, () => {
                        this.props.calculateAvgQtyRate(this.props.productIndex)                    
                    })
                }
            }            
        }
        
        this.setState({ show: false });
        this.props.getGodownModalKey(false)
        // if(this.props.wqdescindex == 1) {
        //     setTimeout(function(){ $(".wqothers").focus(); }, 500);
        // } else {
        //     setTimeout(function(){ $(".wqdiscount").focus(); }, 500);
        // }
    }

    godownOnChange = (i,selectedOption) => {
        let values = [...this.props.selectedProductList];
        if(values[this.props.productIndex]['productGodownBatchData'][i]['godownValue']['value'] != selectedOption.value){
            values[this.props.productIndex]['productGodownBatchData'][i]['godownValue'] = selectedOption
            this.setState({ values, goModal: false}, function() {
                this.refs['qty'+i].focus();
            })
        }        
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

    _createGodownRowKeyPress = (i,e) => {
        let values = [...this.props.selectedProductList];       
        if (e.key === 'Enter' && i == this.props.selectedProductList[this.props.productIndex]['productGodownBatchData'].length - 1) {
            if(this.props.selectedProductList[this.props.productIndex]['productGodownBatchData'][i]['qty'] > 0){
                if(values[this.props.productIndex]['productGodownBatchData'][i]['godownValue'] == ''){
                    toast.error(`Please Select Godown`, {
                        position: "top-right",
                        autoClose: 1000,
                        hideProgressBar: false,
                        closeOnClick: true,
                        pauseOnHover: true,
                        draggable: true
                    });
                }
                else{
                    this.buildRefKey()
                    this.createGodownRow()
                }
                
            }
            else{
                if(i == 0){
                    this.setState({ show: false });
                    this.props.getGodownModalKey(false)
                }
                else{
                    this.handleHide();
                }
                
                
            }
            
        }
        
    }

    createGodownRow = () => {
        let values = [...this.props.selectedProductList];
        var data = {
            godownValue: '',
            allGodownList: values[this.props.productIndex]['ProductAllGodownList'],
            batchValue: '',
            allBatchList: [],
            qty: 0,
            rate: 0,
            grossTotal: 0
        }
        
        values[this.props.productIndex]['productGodownBatchData'].push(data)
        this.setState({values})
    }

    removeGodownRow = (selectIndex) => {
        let values = [...this.props.selectedProductList];
        console.log(values)
        values[this.props.productIndex]['productGodownBatchData'].splice(selectIndex,1)
        this.setState({ values }, () => {
            this.props.calculateAvgQtyRate(this.props.productIndex)
        })
    }

    render() {
        const selectedProductGodownItems = this.props.selectedProductList[this.props.productIndex]['productGodownBatchData'].map((item, i) =>
            <div className="godownRow" key={i}>
                <div className="row form-group">
                    <div className="col-md-3">
                        <Select                            
                            openMenuOnFocus={true}
                            value={item.godownValue}
                            onChange={this.godownOnChange.bind(this,i)}
                            options={item.allGodownList}
                            styles={customLedgerStyles}
                            placeholder="Select Godown"
                            ref={`godownValue${i}`} name={`godownValue${i}`}
                        />
                    </div>
                    <div className="col-md-3">
                        <input type="text" ref={`qty${i}`} name={`qty${i}`} value={item.qty}  className="form-control" placeholder="Quantity" autoComplete="off" onChange={this.quantityOnChange.bind(this,i)}></input> 
                    </div>
                    <div className="col-md-3">
                        <input type="text" ref={`rate${i}`} name={`rate${i}`} value={item.rate}  className="form-control" placeholder="Rate" autoComplete="off" onChange={this.rateOnChange.bind(this,i)} onKeyPress={this._createGodownRowKeyPress.bind(this,i)}></input>                             
                    </div>
                    <div className="col-md-3">
                        <label>{item.grossTotal}</label>
                        {
                            this.props.selectedProductList[this.props.productIndex]['productGodownBatchData'].length > 1 && (
                                <a onClick={() => this.removeGodownRow(i)}><i className='fa fa-trash-o text-danger'></i></a>
                            )
                        }
                    </div>
                </div>
            </div>
        )
        return (
            <React.Fragment>                
                <div className="modal-container" >
                    <Modal
                    show={this.state.show}
                    onHide={this.handleHide}
                    container={this}
                    bsSize="large"
                    aria-labelledby="contained-modal-title-lg"
                    >
                    <Modal.Header closeButton>
                        <Modal.Title id="contained-modal-title-lg">
                            Add Godown
                        </Modal.Title>
                    </Modal.Header>
                    <Modal.Body>
                        <div className="row">
                            <div className="col-md-3">
                                <label>Godown</label>
                            </div>

                            <div className="col-md-3">
                                <label>Qty.</label>
                            </div>
                            <div className="col-md-3">
                                <label>Rate</label>
                            </div>
                            <div className="col-md-3">
                                <label>Value</label>
                            </div>
                        </div>                        
                        {selectedProductGodownItems}
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
  
export default Godown;