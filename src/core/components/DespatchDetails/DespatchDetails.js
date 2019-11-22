import React, { Component, Suspense } from 'react';
import { Link } from "react-router-dom";
import { Button, Modal } from 'react-bootstrap';
import Select from 'react-select';
import * as $ from 'jquery';
import * as transactionService from '../../services/TransactionService';
const customStyles = {
    control: styles => ({ ...styles, backgroundColor: 'white', width: '100%', })
};

class DespatchDetails extends Component {
    constructor(props, context) {
        super(props, context);

        this.handleHide = this.handleHide.bind(this);

        this.state = {
            show: true,
            transportationMode: []
        };
    }

    componentDidMount = () => {
        var data = ['Railways','Roadways','Airways','Waterways','Pipelines'];
        var transportList = [];
        data.forEach(x => {
            var d = {
                value: x,
                label: x,
            }
            transportList.push(d)
        })
        this.setState({
            transportationMode: transportList
        })
        this.buildRefKey()
        console.log(this.props.despatchDetails)
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

    despatchOnChange = (selectedOption) => {
        if(this.props.despatchValue != selectedOption){
            this.props.getDespatchOnChange(selectedOption)
        }
        this.refs['despatch_doc_no'].focus()
            
    }

    handleHide() {
        this.setState({ show: false });
        this.props.getDespatchDetailsModalKey(false)
        // setTimeout(function(){ $(".wqothers").focus(); }, 500);
    }

    despatchDocOnChange = (event) => {
        this.props.despatchFieldOnChange(event.target.name,event.target.value)        
    }
    
    courierGstnOnChange = (event) => {
        this.props.despatchFieldOnChange(event.target.name,event.target.value)
    }
    
    destinationOnChange = (event) => {
        this.props.despatchFieldOnChange(event.target.name,event.target.value)
    }
    
    billLrRrOnChange = (event) => {
        this.props.despatchFieldOnChange(event.target.name,event.target.value)
    }
    
    billLrRrDateOnChange = (event) => {
        this.props.despatchFieldOnChange(event.target.name,event.target.value)
    }
    
    motorVehicleNoOnChange = (event) => {
        this.props.despatchFieldOnChange(event.target.name,event.target.value)
    }
    
    vehicleTypeOnChange = (event) => {
        this.props.despatchFieldOnChange(event.target.name,event.target.value)
    }
    
    transportationModeOnChange = (selectedOption) => {
        console.log(selectedOption)
        this.props.despatchFieldOnChange("transportation_mode",selectedOption)
        this.refs.btn.focus()
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
                        Despatch Details
                        </Modal.Title>
                    </Modal.Header>
                    <Modal.Body>
                        <div className="row">
                            <div className="form-group col-md-6">
                                <label>Despatched Through</label>
                                <Select
                                    openMenuOnFocus={true}
                                    autoFocus={true}
                                    value={this.props.despatchValue}
                                    // value={ {label: this.props.despatchValue, value: this.props.despatchValue} }
                                    onChange={this.despatchOnChange.bind(this)}
                                    options={this.props.allDespatchList} 
                                    styles={customStyles}
                                    placeholder={`Select Despatched Through`}
                                    ref="despatch_through" name="despatch_through"
                                    components = {
                                        {
                                            DropdownIndicator: () => null,
                                            IndicatorSeparator: () => null
                                        }
                                    }
                                />
                                {/* <input type="text" className="form-control" placeholder="Despatched Through" name="despatch_through" ref="despatch_through" autoComplete="off" value={this.props.despatchValue} /> */}
                            </div>
                            <div className="form-group col-md-6">
                                <label>Despatch Doc. No.</label>
                                <input type="text" className="form-control despatch_doc_no" placeholder="Despatch Doc. No." name="despatch_doc_no" ref="despatch_doc_no" autoComplete="off" value={this.props.despatchDetails['despatch_doc_no']} onChange={this.despatchDocOnChange.bind(this)}/>
                            </div>
                            <div className="form-group col-md-6">
                                <label>GSTN</label>
                                <input type="text" className="form-control" placeholder="GSTN" name="courier_gstn" ref="courier_gstn" autoComplete="off" value={this.props.despatchDetails['courier_gstn']} onChange={this.courierGstnOnChange.bind(this)}/>
                            </div>
                            <div className="form-group col-md-6">
                                <label>Destination</label>
                                <input type="text" className="form-control" placeholder="Destination" name="destination" ref="destination" autoComplete="off" value={this.props.despatchDetails['destination']} onChange={this.destinationOnChange.bind(this)}/>
                            </div>
                            <div className="form-group col-md-4">
                                <label>Bill of Lading/LR-RR No.</label>
                                <input type="text" className="form-control" placeholder="Bill of Lading/LR-RR No." name="bill_lr_rr" ref="bill_lr_rr" autoComplete="off" value={this.props.despatchDetails['bill_lr_rr']} onChange={this.billLrRrOnChange.bind(this)}/>
                            </div>
                            <div className="form-group col-md-4">
                                <label>Date</label>
                                <input type="text" className="form-control" placeholder="DD/MM/YYYY" name="bill_lr_rr_date" ref="bill_lr_rr_date" autoComplete="off" value={this.props.despatchDetails['bill_lr_rr_date']} onChange={this.billLrRrDateOnChange.bind(this)}/>
                            </div>
                            <div className="form-group col-md-4">
                                <label>Motor Vehicle No.</label>
                                <input type="text" className="form-control" placeholder="Motor Vehicle No." name="motor_vehicle_no" ref="motor_vehicle_no" autoComplete="off" value={this.props.despatchDetails['motor_vehicle_no']} onChange={this.motorVehicleNoOnChange.bind(this)}/>
                            </div>
                            <div className="form-group col-md-6">
                                <label>Vehicle Type</label>
                                <input type="text" className="form-control" placeholder="Vehicle Type" name="vehicle_type" ref="vehicle_type" autoComplete="off" value={this.props.despatchDetails['vehicle_type']} onChange={this.vehicleTypeOnChange.bind(this)}/>
                            </div>
                            <div className="form-group col-md-6">
                                <label>Transportation Mode</label>
                                <Select
                                    openMenuOnFocus={true}
                                    value={this.props.despatchDetails['transportation_mode']}
                                    onChange={this.transportationModeOnChange.bind(this)}
                                    options={this.state.transportationMode} 
                                    styles={customStyles}
                                    placeholder={`Select Transportation Mode`}
                                    ref="transportation_mode" name="transportation_mode"
                                    components = {
                                        {
                                            DropdownIndicator: () => null,
                                            IndicatorSeparator: () => null
                                        }
                                    }
                                />
                                {/* <input type="text" className="form-control" placeholder="Transportation Mode" name="transportation_mode" ref="transportation_mode" autoComplete="off" value={this.props.despatchDetails['transportation_mode']} onChange={this.transportationModeOnChange.bind(this)}/> */}
                            </div>
                        </div>
                    </Modal.Body>
                    <Modal.Footer>
                        <button onClick={this.handleHide} className="btn btn-primary" name="btn" ref="btn">Save</button>
                    </Modal.Footer>
                    </Modal>
                </div>
            </React.Fragment>
        );
    }
}
  
export default DespatchDetails;