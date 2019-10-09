import React, { Component, Suspense } from 'react';
import * as transactionService from '../../services/TransactionService';
import './settingSideBar.css';
import Select from 'react-select';
const Currency = React.lazy(() => import('../Currency'));
const customStyles = {
    control: styles => ({ ...styles, backgroundColor: 'white', width: '100%', })
};

class SettingSideBar extends Component {
    constructor(props) {
        super(props);
        
        this.state = {
            selectedCurrency: -1,
            formSubmissionOption: 1,
            postDatedEntryOption: 0,
            reverseEntryWithRespectToBranchOption: 0,
            taxForDifferentExportCountryOption: 0,
            productDescReadOnly: 0,
            productDiscReadOnly: 0,
            formSettingData: {},
            CurrencyList: [],
            modalCurrencyKey: false,
            selectedCurrencyObj: {},
            base_currency: 0,
            base_currency_unit: 0,
            currencyAreaVisible: false
        }
        
        this.closeSettingSideBar = this.closeSettingSideBar.bind(this)
        this.updateFormData = this.updateFormData.bind(this)
        // this.onCurrencyChange = this.onCurrencyChange.bind(this)
        this.handleFormSubmissionOptionChange = this.handleFormSubmissionOptionChange.bind(this)
        this.handleReverseEntryWithRespectToBranchOptionChange = this.handleReverseEntryWithRespectToBranchOptionChange.bind(this)
        this.handleTaxForDifferentExportCountryOptionChange = this.handleTaxForDifferentExportCountryOptionChange.bind(this)
        this.handleProductDescReadOnlyOptionChange.bind(this)
        this.handleProductDiscReadOnlyOptionChange.bind(this)
    }

    

    componentDidMount = () => {        
        this.getAllCurrency();
    }

    getAllCurrency(){
        transactionService.getAllCurrency().then(res => {
            console.log(res.data)
            console.log(this.props.preferencesDetails)
            const options = []
            res.data.forEach(o => {
                var d = {
                    value: o.id,
                    label: o.currency,
                    data: o
                }
                options.push(d)
            })
            this.setState({CurrencyList: options}, () => {
                var index = this.state.CurrencyList.findIndex(x => x.value == this.props.preferencesDetails.selected_currency)
                console.log(index)
                if(index != -1){
                    this.setState({
                        selectedCurrencyObj: this.state.CurrencyList[index]['data'],
                        selectedCurrency: this.state.CurrencyList[index],
                        base_currency: +this.state.CurrencyList[index]['value'],
                        base_currency_unit: +this.state.CurrencyList[index]['data']['unit_price']
                    }, () => {
                        this.updateFormData();
                    })
                }
                
            })
        })
    }

    changeCurrencyValue = (data) => {
        this.setState({
            selectedCurrencyObj: data
        })
    }

    getCurrencyModalKey = (key) => {
        this.setState({
            modalCurrencyKey: key
        }, () => {
            this.updateFormData()
        })
    }
        
    closeSettingSideBar = () => {        
        this.props.getSideBarKey(false)        
    }

    updateFormData = () => {
        this.setState({
            formSettingData: {
                selectedCurrency: +this.state.selectedCurrency.value,
                selectedCurrencyUnit: +this.state.selectedCurrencyObj.unit_price,
                selectedCurrencyName: this.state.selectedCurrencyObj.currency_name,
                base_currency: this.state.base_currency,
                base_currency_unit: this.state.base_currency_unit,
                formSubmissionOption: this.state.formSubmissionOption,
                postDatedEntryOption: this.state.postDatedEntryOption,
                reverseEntryWithRespectToBranchOption: this.state.reverseEntryWithRespectToBranchOption,
                taxForDifferentExportCountryOption: this.state.taxForDifferentExportCountryOption,
                productDescReadOnly: this.state.productDescReadOnly,
                productDiscReadOnly: this.state.productDiscReadOnly
            }
        }, () =>{
            this.props.getFormSettingData(this.state.formSettingData);
        })
    }

    onCurrencyChange = (selectedOption) => {
        console.log(selectedOption)
        this.setState({selectedCurrency: selectedOption}, () =>{
            var index = this.state.CurrencyList.findIndex(x => x.value == this.state.selectedCurrency.value)
            if(index != -1){
                this.setState({
                    selectedCurrencyObj: this.state.CurrencyList[index]['data']
                }, () => {
                    this.setState({modalCurrencyKey: true})
                    this.updateFormData();
                })
            }
            
        });
    }
    handleFormSubmissionOptionChange = (e) => {        
        this.setState({ formSubmissionOption: +e.target.value }, () => {
            this.updateFormData();
        });     
    }
    handlePostDatedEntryOptionChange = (e) => {
        this.setState({postDatedEntryOption: +e.target.value}, () =>{
            this.updateFormData();
        });
    }
    handleReverseEntryWithRespectToBranchOptionChange = (e) => {
        this.setState({reverseEntryWithRespectToBranchOption: +e.target.value}, () =>{
            this.updateFormData();
        });
    }
    handleTaxForDifferentExportCountryOptionChange = (e) => {
        console.log(+e.target.value)
        if(+e.target.value == 1){
            this.setState({currencyAreaVisible: true})
        }
        else{
            this.setState({currencyAreaVisible: false})
        }
        this.setState({taxForDifferentExportCountryOption: +e.target.value}, () =>{
            this.updateFormData();
        });
    }

    handleProductDescReadOnlyOptionChange = (e) => {
        this.setState({productDescReadOnly: +e.target.value}, () =>{
            this.updateFormData();
        });
    }

    handleProductDiscReadOnlyOptionChange = (e) => {
        this.setState({productDiscReadOnly: +e.target.value}, () =>{
            this.updateFormData();
        });
    }

    loading = () => <div className="animated fadeIn pt-1 text-center">Loading...</div>

    render() {
        var currenciesOptions = this.state.CurrencyList.map((currency, i) => {
            return (
                <option key={i} value={currency.id}>{currency.currency}</option>
            )
        })
        return (
            <React.Fragment>
                <button className="btn btn-xs btn-danger closeRightBar" onClick={this.closeSettingSideBar}><i className="fa fa-times"></i></button>
                <form >
                    <div className="form-group">
                        <label >Form Submission</label>
                        <div className="form-group">
                            <div className="radio">
                                <label><input type="radio" value="1" checked={this.state.formSubmissionOption === 1}  name="activity_submit" autoComplete="off" onChange={this.handleFormSubmissionOptionChange}/>Submit &amp; Show New Form</label>
                            </div>
                            <div className="radio">
                                <label><input type="radio" value="2" checked={this.state.formSubmissionOption === 2} name="activity_submit" autoComplete="off" onChange={this.handleFormSubmissionOptionChange}/>Submit &amp; Show List</label>
                            </div>
                            <div className="radio">
                                <label><input type="radio" value="3" checked={this.state.formSubmissionOption === 3} name="activity_submit" autoComplete="off" onChange={this.handleFormSubmissionOptionChange}/>Submit &amp; View</label>
                            </div>
                        </div>
                    </div>

                    <div className="form-group">
                        <label >Adder Account</label>
                        <div className="form-group">
                            <a href="javascript:void(0);" className="new-ledger-btn">Add Ledger</a><span className="pull-right text-muted">Ctrl+Shift+l</span><br/>
                            <a href="javascript:void(0);" className="add-group-btn">Add Group</a><span className="pull-right text-muted">Ctrl+Alt+g</span><br/>
                            {/* <a href="" target="_blank">Add Category</a><br/>
                            <a href="" target="_blank">Add Attribute</a><br/>
                            <a href="" target="_blank">Add Unit</a><br/>
                            <a href="" target="_blank">Add Product</a><br/>
                            <a href="" target="_blank">Add Service</a> */}
                        </div>
                    </div>
                    {
                        this.state.currencyAreaVisible && (
                            <div className="form-group">
                                <label >Select currency</label>
                                <div className="form-group">
                                    <Select
                                        openMenuOnFocus={true}
                                        value={this.state.selectedCurrency}
                                        onChange={this.onCurrencyChange.bind(this)}
                                        options={this.state.CurrencyList} 
                                        styles={customStyles}
                                        placeholder="Select Currency"
                                        ref="currency" name="currency"
                                    />
                                </div>
                            </div>
                        )
                    }
                    
                    {
                        this.state.modalCurrencyKey && (
                            <Suspense  fallback={this.loading()}>                            
                                <Currency selectedCurrencyObj={this.state.selectedCurrencyObj} getCurrencyModalKey={this.getCurrencyModalKey} changeCurrencyValue={this.changeCurrencyValue}/>
                            </Suspense>
                        )
                    }
                    <div className="form-group">
                        <label >Make this entry as post dated entry?</label>
                        <div className="form-group">
                            <label className="radio-inline"><input type="radio" value="1" checked={this.state.postDatedEntryOption === 1}  name="postdated" autoComplete="off" onChange={this.handlePostDatedEntryOptionChange}/>Yes</label>
                            <label className="radio-inline"><input type="radio" value="0" checked={this.state.postDatedEntryOption === 0}  name="postdated" autoComplete="off" onChange={this.handlePostDatedEntryOptionChange}/>No</label>
                        </div>
                    </div>

                    <div className="form-group">
                        <label >Make Product Description?</label>
                        <div className="form-group">
                            <label className="radio-inline"><input type="radio" value="1" checked={this.state.productDescReadOnly === 1}  name="productDesc" autoComplete="off" onChange={this.handleProductDescReadOnlyOptionChange}/>Hide</label>
                            <label className="radio-inline"><input type="radio" value="0" checked={this.state.productDescReadOnly === 0}  name="productDesc" autoComplete="off" onChange={this.handleProductDescReadOnlyOptionChange}/>Show</label>
                        </div>
                    </div>

                    <div className="form-group">
                        <label >Make Product Discount?</label>
                        <div className="form-group">
                            <label className="radio-inline"><input type="radio" value="1" checked={this.state.productDiscReadOnly === 1}  name="productDisc" autoComplete="off" onChange={this.handleProductDiscReadOnlyOptionChange}/>Hide</label>
                            <label className="radio-inline"><input type="radio" value="0" checked={this.state.productDiscReadOnly === 0}  name="productDisc" autoComplete="off" onChange={this.handleProductDiscReadOnlyOptionChange}/>Show</label>
                        </div>
                    </div>

                    <div className="form-group">
                        <label >Do you want reverse entry with respect to branch?</label>
                        <div className="form-group">
                            <label className="radio-inline"><input type="radio" value="1" checked={this.state.reverseEntryWithRespectToBranchOption === 1}  name="select_branch_entry_no" className="branch-entry-no" autoComplete="off" onChange={this.handleReverseEntryWithRespectToBranchOptionChange}/>Yes</label>
                            <label className="radio-inline"><input type="radio" value="0" checked={this.state.reverseEntryWithRespectToBranchOption === 0}  name="select_branch_entry_no"  className="branch-entry-no" autoComplete="off" onChange={this.handleReverseEntryWithRespectToBranchOptionChange}/>No</label>
                        </div>
                    </div>
                    <div className="form-group">
                        <label >Do you want to set tax 0 for different export country?</label>
                        <div className="form-group">
                            <label className="radio-inline"><input type="radio" value="1" checked={this.state.taxForDifferentExportCountryOption === 1} name="tax_status_country" className="tax-status-country" autoComplete="off" onChange={this.handleTaxForDifferentExportCountryOptionChange}/>Yes</label>
                            <label className="radio-inline"><input type="radio" value="0" checked={this.state.taxForDifferentExportCountryOption === 0} name="tax_status_country" className="tax-status-country" autoComplete="off" onChange={this.handleTaxForDifferentExportCountryOptionChange}/>No</label>
                        </div>
                    </div>
                </form>
            </React.Fragment>
        );
    }
}
  
export default SettingSideBar;