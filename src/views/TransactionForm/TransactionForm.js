import React, { Component, Suspense } from 'react';
import { HotKeys } from 'react-hotkeys';
import * as transactionService from '../../core/services/TransactionService';
import Autocomplete from 'react-autocomplete';
import './TransactionForm.css';
import * as moment from 'moment';
import { ToastContainer, toast } from 'react-toastify';
import chroma from 'chroma-js';
import Select from 'react-select';
import * as $ from 'jquery';

const Breadcrumb = React.lazy(() => import('../../core/components/Breadcrumb'));

const BankDetails = React.lazy(() => import('../../core/components/BankDetails'));
const DespatchDetails = React.lazy(() => import('../../core/components/DespatchDetails'));
const ChangeShippingAddress = React.lazy(() => import('../../core/components/ChangeShippingAddress'));

const GodownBatch = React.lazy(() => import('../../core/components/GodownBatch'));

const Godown = React.lazy(() => import('../../core/components/Godown'));

const keyMap = {
    'bankDetails': 'ctrl+alt+b',
    'despatchDetails': 'ctrl+alt+c',
    'changeShippingAddress': 'ctrl+alt+m'
};
  
const customStyles = {
    control: styles => ({ ...styles, backgroundColor: 'white', width: 130, })
};

const customLedgerStyles = {
    control: styles => ({ ...styles, backgroundColor: 'white', width: '100%', })
};

class TransactionForm extends Component {
  constructor(props) {
    super(props);
    this._handleKeyPress = this._handleKeyPress.bind(this);
    this.state = {
      title: '',
      action: '',
      tran_type: 0,
      date: '',
      financial_year: null,
      modalKey: false,
      debtorsValue: '',
      salesValue: '',
      debtorsValue_entry_id: '',
      salesValue_entry_id: '',
      allLedgerDebtorsList: [],
      shippingDetails: null,
      shippingDetailsKey: false,
      billing_address: null,
      shipping_address: null,
      ledgerStateCode: '',
      allLedgerSalesList: [],
      transactionParameters: {
        action:'',
        entry_type:'',
        bank_id:'',
        parent_id:'',
        voucher:'',
        voucher_id:'',
        transaction_type_id:'',
        auto_no_status:'',
        auto_date:'',
        recurring:'',
        godown_status:'',
        batch_status:'',
        ledger_code_status:''
      },
      allProductList: [],
      productValue: [],
      selectedExpenseList: [],
      selectedProductList: [],
      ledgerDebtorsTitle: '',
      ledgerSalesTitle: '',
    //   loading: true,
      allExpenseList: [],
      expense: [],
      selectedProductTotalValue: 0,
      selectedItemTotalValue: 0,
      selectedItemGrossValue: 0,
      selectedItemTaxValue: 0,
      termsConditions: 'Goods once sold cannot be returned except manufacturing defects.',
      notes: '',
      others: '',
      invoiceNumber: '',
      refDate: '',
      refNo: '',
      recurring: '',
      advanceBillName: '',
      modalGodownBatchKey: false,
      modalGodownKey: false,
      productIndex: '',
      companyInfo: {
        type: '',
        state: ''
      },
      cgstColKey: false,
      sgstColKey: false,
      cessColKey: false,
      igstColKey: false,
      taxValColKey: false,
      userDetails: null,
      formSettingData: {
        productDescReadOnly: 0,
        productDiscReadOnly: 0,
      },
      transaction_id: '',
      allSalesManList: [],
      salesManValue: '',
      despatchDetailsModalKey: false,
      changeShippingAddressModalKey: false,
      contactValue: null,
      shippingValue: null,
      allContactList: [],
      shippingAddressByContact: null,
      allDespatchList: [],
      despatchValue: null,
      despatchDetails: {
        despatch_through: '',
        despatch_doc_no: '',
        courier_gstn: '',
        destination: '',
        bill_lr_rr: '',
        bill_lr_rr_date: '',
        motor_vehicle_no: '',
        vehicle_type: '',
        transportation_mode: ''
      },
      preferencesDetails: null,
      bankDetailsModalKey: false,
      bankValue: null,
      allBankList: [],
      editdetails: []
    }
  }

  loading = () => <div className="animated fadeIn pt-1 text-center">Loading...</div>

  componentDidMount = () => {
    this.buildRefKey()    
    var location = this.props.match.params.name;
    var current_page = location.split('-');
    var page_title = ''
    if(current_page.length > 2){
        page_title = current_page[0] + ' ' + current_page[1]
    }
    else{
        page_title = current_page[0]
    }
    page_title = page_title.toLowerCase().replace(/\b[a-z]/g, (letter) => {
        return letter.toUpperCase();
    });
    document.title = "ACT | " + page_title;
    // var financialYear = {
    //     form: {
    //         month: '04',
    //         year: '2018'
    //     },
    //     to: {
    //         month: '03',
    //         year: '2019'
    //     }
    // }
    var transactionId = '';
    var action_title = '';
    if(this.props.match.params.id){
        transactionId = this.props.match.params.id;
        action_title = "Edit"
    }
    else{
        action_title = "Add"
    }
    this.setState({
        title: page_title,
        action: action_title,
        tran_type: this.props.match.params.tran_type,
        // financial_year: financialYear,
        cgstColKey: false,
        sgstColKey: false,
        cessColKey: false,
        igstColKey: false,
        taxValColKey: false,
        transaction_id: transactionId
    }, () => {
        this.getLedgerDebtorsList()
        this.getLedgerSalesList()
        this.getTransactionParameters()
        this.getProductList()
        this.getSalesManList()
        this.getdiscountList()
        this.getUserDetails()
        this.getAllContactsList()
        this.getAllDespatchList()
        this.getPreferencesDetails()
        this.getBankListForTransaction()
        if(this.state.tran_type == 5 || this.state.tran_type == 7 || this.state.tran_type == 10){
            this.setState({
                ledgerDebtorsTitle: 'Debtors',
                ledgerSalesTitle: 'Sales'
            })
        }
        else if(this.state.tran_type == 6 || this.state.tran_type == 8 || this.state.tran_type == 9){
            this.setState({
                ledgerDebtorsTitle: 'Creditor',
                ledgerSalesTitle: 'Purchase'
            })
        }
    })
  }  

  componentDidUpdate = (prevProps) => {
    this.buildRefKey()   
    if (prevProps.location.pathname !== this.props.location.pathname) {
        var location = this.props.match.params.name;
        var current_page = location.split('-');
        var page_title = ''
        if(current_page.length > 2){
            page_title = current_page[0] + ' ' + current_page[1]
        }
        else{
            page_title = current_page[0]
        }
        page_title = page_title.toLowerCase().replace(/\b[a-z]/g, (letter) => {
            return letter.toUpperCase();
        });
        document.title = "ACT | " + page_title;
        // var financialYear = {
        //     form: {
        //         month: '04',
        //         year: '2018'
        //     },
        //     to: {
        //         month: '03',
        //         year: '2019'
        //     }
        // }
        var despatchDetailsVal = {
            despatch_through: '',
            despatch_doc_no: '',
            courier_gstn: '',
            destination: '',
            bill_lr_rr: '',
            bill_lr_rr_date: '',
            motor_vehicle_no: '',
            vehicle_type: '',
            transportation_mode: ''
        }
        var transactionId = '';
        var action_title = '';
        if(this.props.match.params.id){
            transactionId = this.props.match.params.id;
            action_title = "Edit"
        }
        else{
            action_title = "Add"
        }        
        this.setState({
            title: page_title,
            action: action_title,
            tran_type: this.props.match.params.tran_type,
            transaction_id: transactionId,
            date: '',
            // financial_year: financialYear,
            modalKey: false,
            debtorsValue: '',
            salesValue: '',
            allLedgerDebtorsList: [],
            shippingDetails: null,
            shippingDetailsKey: false,
            billing_address: null,
            shipping_address: null,
            ledgerStateCode: '',
            allLedgerSalesList: [],
            transactionParameters: {
                action:'',
                entry_type:'',
                bank_id:'',
                parent_id:'',
                voucher:'',
                voucher_id:'',
                transaction_type_id:'',
                auto_no_status:'',
                auto_date:'',
                recurring:'',
                godown_status:'',
                batch_status:'',
                ledger_code_status:''
            },
            allProductList: [],
            productValue: [],
            selectedExpenseList: [],
            selectedProductList: [],
            ledgerDebtorsTitle: '',
            ledgerSalesTitle: '',
            // loading: true,
            expense: [],
            selectedProductTotalValue: 0,
            selectedItemTotalValue: 0,
            selectedItemGrossValue: 0,
            selectedItemTaxValue: 0,
            termsConditions: 'Goods once sold cannot be returned except manufacturing defects.',
            notes: '',
            invoiceNumber: '',
            refDate: '',
            refNo: '',
            recurring: '',
            advanceBillName: '',
            modalGodownBatchKey: false,
            modalGodownKey: false,
            productIndex: '',
            cgstColKey: false,
            sgstColKey: false,
            cessColKey: false,
            igstColKey: false,
            taxValColKey: false,
            allSalesManList: [],
            salesManValue: '',
            despatchDetailsModalKey: false,
            changeShippingAddressModalKey: false,
            contactValue: null,
            shippingValue: null,
            allContactList: [],
            shippingAddressByContact: null,
            allDespatchList: [],
            despatchValue: null,
            despatchDetails: despatchDetailsVal,
            preferencesDetails: null,
            bankDetailsModalKey: false,
            bankValue: null,
            wqbankName: '',
            allBankList: []
        }, () => {
            this.getLedgerDebtorsList()
            this.getLedgerSalesList()
            this.getTransactionParameters()
            this.getProductList()
            this.getSalesManList()
            this.getdiscountList()
            this.getUserDetails()
            this.getAllContactsList()
            this.getAllDespatchList()
            this.getPreferencesDetails()
            this.getBankListForTransaction()
            if(this.state.tran_type == 5 || this.state.tran_type == 7 || this.state.tran_type == 10){
                this.setState({
                    ledgerDebtorsTitle: 'Debtors',
                    ledgerSalesTitle: 'Sales'
                })
            }
            else if(this.state.tran_type == 6 || this.state.tran_type == 8 || this.state.tran_type == 9){
                this.setState({
                    ledgerDebtorsTitle: 'Creditor',
                    ledgerSalesTitle: 'Purchase'
                })
            }
        })
    }    
  }
  
  
  getTransactionDataById(id){
    var params = "?id=" + id + "&type_id=" + this.state.tran_type + "&action=e";
    transactionService.getTransactionDataById(params).then(res => {        
        var data = res.data[0]
        console.log(data)
        console.log(data.entry_details)
        console.log(data.productData)
        //console.log("===>>>",this.state.action)
        this.setState({
            editdetails: data,
            invoiceNumber: data.entry.entry_no,
            date: data.entry.create_date,
            refNo: data.entry.voucher_no,
            refDate: data.entry.voucher_date,
            notes: data.entry.narration,
            termsConditions: data.order.terms_and_conditions,
            // despatchValue: data.courier.despatch_through,
            despatchDetails: data.courier,
            bankValue: data.entry.bank_id,
            wqbankName: data.entry.bank_name
        })
        console.log("bankValue1=>",this.state.bankValue);
        
        // ledgerDebtors
        var ledgerDebtorsIndex = this.state.allLedgerDebtorsList.findIndex(x => x.data.id == data.entry_details[0].ladger_id)
        if(ledgerDebtorsIndex != -1){
            this.setState({
                debtorsValue: this.state.allLedgerDebtorsList[ledgerDebtorsIndex],
                debtorsValue_entry_id: data.entry_details[0].id
            }, () => {
                this.getShippingDetails(this.state.allLedgerDebtorsList[ledgerDebtorsIndex]['data']['id'])
            })
        }
        // ledgerSales
        var ledgerSalesIndex = this.state.allLedgerSalesList.findIndex(x => x.data.id == data.entry_details[1].ladger_id)
        if(ledgerSalesIndex != -1){
            this.setState({
                salesValue: this.state.allLedgerSalesList[ledgerSalesIndex],
                salesValue_entry_id: data.entry_details[1].id
            })
        }
        // SalesMan
        if(data.sales_man_dateils != null) {
            var manSalesIndex = this.state.allSalesManList.findIndex(x => x.data.id == data.sales_man_dateils.id)
            if(manSalesIndex != -1){
                this.setState({
                    salesManValue: this.state.allSalesManList[manSalesIndex]
                })
            }
        }
        // Expense
        data.entry_details.forEach(s => {
            var expenseIndex = this.state.allExpenseList.findIndex(x => x.data.id == s.ladger_id)
            if(expenseIndex != -1){
                var expenseValues = [...this.state.expense]
                expenseValues.push(this.state.allExpenseList[expenseIndex])
                this.setState({
                    expense: expenseValues
                }, () => {
                    let values = [...this.state.expense];
                    values.forEach(x => {        
                        var selectIndex = this.state.selectedExpenseList.findIndex(y => y.id == x.value);
                        if(selectIndex == -1){
                            var expenseData = {
                                expenseValue_entry_id: s.id, 
                                id: x.value,
                                ladger_name: x.label,
                                price: parseInt(s.balance),
                                data: x.data
                            }
                            this.state.selectedExpenseList.push(expenseData)
                            this.setState({
                                selectedExpenseList: this.state.selectedExpenseList
                            }, () => {
                                this.buildRefKey()
                                this.calculateSumValue()
                            })
                        }
                    })
                })
            }
        })

        // product
        data.productData.forEach(y => {
            var productIndex = this.state.allProductList.findIndex(x => x.data.product_id == y.product_id)
            if(productIndex != -1){
                var productValues = [...this.state.productValue]
                productValues.push(this.state.allProductList[productIndex])
                this.setState({
                    productValue: productValues
                }, () => {
                    var selectedProductValIndex = this.state.productValue.findIndex(z => z.data.product_id == y.product_id);
                    var selectedProductListIndex = this.state.selectedProductList.findIndex(m => m.detailsData.product_id == y.product_id);
                    if(selectedProductListIndex == -1){
                        var unitParams = "?unit_id=" + this.state.productValue[selectedProductValIndex].data.id;
                        transactionService.getComplexUnitById(unitParams).then(res => {
                            var allUnits = res.data;
                            const options = []
                            allUnits.forEach(n => {
                                var d = {
                                    value: n.id,
                                    label: n.value
                                }
                                options.push(d)
                            })
                            if(options.length == 0){
                                var d = {
                                    value: this.state.productValue[selectedProductValIndex].data.productUnitId,
                                    label: this.state.productValue[selectedProductValIndex].data.productUnitName
                                }
                                options.push(d)
                            }
                            var unitOptionIndex = options.findIndex(g => g.value == y.unit_id);
                            var selectUnitoption;
                            if(unitOptionIndex != -1){
                                selectUnitoption = options[unitOptionIndex]
                            }
                            var selectedProductGodownBatchData = [];
                            console.log(y)
                            var godownData = [];               
                            var godownParams = "?pid=" + this.state.productValue[selectedProductValIndex].data.id;
                            console.log(godownParams)
                            transactionService.getGodownListById(godownParams).then(res => {
                                console.log(res.data)
                                res.data.forEach(o => {
                                    var d = {
                                        value: o.id,
                                        label: o.value,
                                        data: o
                                    }
                                    godownData.push(d)
                                })
                                console.log(godownData)
                                if(y.having_batch == 1){
                                    y.batch_details.forEach(c => {

                                        var batchData = [];
                                        var batchParams = "?pid=" + this.state.productValue[selectedProductValIndex].data.id + "&gid=" + c.godown_id;
                                        transactionService.getBatchByGodownIdAndProductId(batchParams).then(res => {
                                            console.log(res.data)
                                            res.data.forEach(p => {
                                                var d = {
                                                    value: p.id,
                                                    label: p.value,
                                                    data: p
                                                }
                                                batchData.push(d)
                                            })
                                            console.log(batchData)
                                            var godownIndex = godownData.findIndex(d => d.data.id == c.godown_id);
                                            console.log(godownIndex)
                                            var batchIndex = batchData.findIndex(e => e.data.id == c.parent_id);
                                            console.log(batchIndex)
                                            if(godownIndex != -1 && batchIndex != -1){
                                                var bd = {
                                                    godownValue: godownData[godownIndex],
                                                    allGodownList: [],
                                                    batchValue: batchData[batchIndex],
                                                    allBatchList: [],
                                                    qty: +c.quantity,
                                                    rate: +c.rate,
                                                    grossTotal: +c.value
                                                }
                                                selectedProductGodownBatchData.push(bd)
                                            }
                                        })
                                        
                                    })
                                }
                                else{
                                    y.godown_details.forEach(f => {
                                        var godownIndex = godownData.findIndex(d => d.data.id == f.godown_id);
                                        if(godownIndex != -1){
                                            var wqgrossTotal = 0;
                                            wqgrossTotal = parseInt(f.quantity_out) * parseInt(f.transaction_price)
                                            var bd = {
                                                godownValue: godownData[godownIndex],
                                                allGodownList: [],
                                                batchValue: '',
                                                allBatchList: [],
                                                qty: +parseInt(f.quantity_out),
                                                rate: +parseInt(f.transaction_price),
                                                grossTotal: wqgrossTotal
                                            }
                                            selectedProductGodownBatchData.push(bd)
                                        }
                                    })
                                }
                                console.log(selectedProductGodownBatchData)
                            })
                            

                            var prodData = {
                                entry_product_id: y.id,
                                id: this.state.productValue[selectedProductValIndex].data.id,
                                name: this.state.productValue[selectedProductValIndex].data.name,
                                sku: this.state.productValue[selectedProductValIndex].data.sku,
                                description: this.state.productValue[selectedProductValIndex].data.shortDescription,
                                units: options,
                                unit: selectUnitoption,
                                godownValue: '',
                                ProductAllGodownList: godownData,
                                batchValue: '',
                                productGodownBatchData: selectedProductGodownBatchData,
                                qty: +y.quantity,
                                stock: this.state.productValue[selectedProductValIndex].data.stock,
                                rate: +y.base_price,
                                discount: '',
                                grossTotal: 0,
                                cgst: 0,
                                sgst: 0,
                                igst: 0,
                                cess: 0,
                                cessRate: 0,
                                taxValue: 0,
                                total: 0,
                                detailsData: this.state.productValue[selectedProductValIndex].data
                            }
                            this.state.selectedProductList.push(prodData)
                            this.setState({
                                selectedProductList: this.state.selectedProductList
                            }, () => {
                                var index = this.state.selectedProductList.findIndex(k => k.id == this.state.productValue[selectedProductValIndex].value);
                                this.calculateGross(index);
                            })
                        }) 
                    }
                })
                
            }
            
        })
    })
  }

  getUserDetails(){
    transactionService.getUserDetails().then(res => {
        console.log(res.data)
        this.setState({userDetails: res.data[0]}, () => {
            let values = {...this.state.companyInfo};
            values['type'] = this.state.userDetails['company_type'];
            var financialYear = this.state.userDetails['financialYear']
            this.setState({
                companyInfo: values,
                financial_year: financialYear
            }, () => {
                console.log(this.state.financial_year)
            })
        })
        
    })
  }

  getPreferencesDetails(){
    transactionService.getPreferencesDetails().then(res => {
        console.log(res.data)
        this.setState({preferencesDetails: res.data})
    })
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
        console.log("yess===>>>",this.refs)
      for (let x in this.refs) {        
        if(this.refs.hasOwnProperty(x)){
            arr.push(x);
        }        
      }
      
      for(var i = 0 ; i< arr.length - 1; i++){
        console.log("00000===>>>",arr[i]);
        if(this.refs[field.name].name === arr[i]){
            if(this.refs[field.name].name === 'termsConditions') {
                this.formSubmit();
            } else {
                console.log(this.refs[field.name].name)
                this.refs[arr[i+1]].focus();
            }
        }
      }
    }
  }

  getLedgerDebtorsList(){
    var params = "?tran_type=" + this.state.tran_type;
    transactionService.getLedgerDebtorsList(params).then(res => {
        var ledgerData = [];
        res.data.forEach(x => {
            var d = {
                value: x.id,
                // label: `${x.ladger_name} (${x.ledger_code}) [Cur. Bal. ${x.current_closing_balance} - ${x.account}]`,
                label: `${x.ladger_name} (${x.ledger_code})`,
                data: x
            }
            ledgerData.push(d)
        })
        
        this.setState({
            allLedgerDebtorsList: ledgerData
        })        
        console.log(res.data)
    })
  }

  ledgerOnChange = (selectedOption) => {
    if(this.state.transactionParameters.auto_date != 1 && this.state.date == ''){
        toast.error(`Please Enter Date`, {
            position: "top-right",
            autoClose: 1000,
            hideProgressBar: false,
            closeOnClick: true,
            pauseOnHover: true,
            draggable: true
        });
    }
    else if(this.state.transactionParameters.auto_no_status != 1 && this.state.invoiceNumber == ''){
        toast.error(`Please Enter Invoice no.`, {
            position: "top-right",
            autoClose: 1000,
            hideProgressBar: false,
            closeOnClick: true,
            pauseOnHover: true,
            draggable: true
        });
    }
    else{
        this.setState({ debtorsValue: selectedOption});
        this.getShippingDetails(selectedOption.value)
        // $(".wqSalesSelect").focus();
        this.refs.salesValue.focus();
    }
    
  }

  salesOnChange = (selectedOption) => {
    if(this.state.debtorsValue == ''){        
        toast.error(`Please Select ${this.state.ledgerDebtorsTitle}`, {
            position: "top-right",
            autoClose: 1000,
            hideProgressBar: false,
            closeOnClick: true,
            pauseOnHover: true,
            draggable: true
        });
    }
    else {
        this.setState({ salesValue: selectedOption})
        this.refs.advanceBillName.focus();
    }    
  }  

  getLedgerSalesList(){
    var params = "?tran_type=" + this.state.tran_type;
    transactionService.getLedgerSalesList(params).then(res => {
        var ledgerData = [];
        res.data.forEach(x => {
            var d = {
                value: x.id,
                // label: `${x.ladger_name} (${x.ledger_code}) [Cur. Bal. ${x.current_closing_balance} - ${x.account}]`,
                label: `${x.ladger_name} (${x.ledger_code})`,
                data: x
            }
            ledgerData.push(d)
        })
        
        this.setState({
            allLedgerSalesList: ledgerData
        })        
        console.log(res.data)
    })
  }

  getColKey = () => {
    // console.log(this.state.companyInfo.type)
    // console.log(this.state.companyInfo.state)
    // console.log(this.state.ledgerStateCode)
    if(this.state.companyInfo.type == 1){
       
        if(this.state.companyInfo.state == this.state.ledgerStateCode){
            this.setState({
                cgstColKey: true,
                sgstColKey: true,
                cessColKey: true,
                igstColKey: false,
                taxValColKey: true
            })
        }
        else{
            this.setState({
                cgstColKey: false,
                sgstColKey: false,
                cessColKey: true,
                igstColKey: true,
                taxValColKey: true
            })
        }
        
    }
    else{
        this.setState({
            cgstColKey: false,
            sgstColKey: false,
            cessColKey: false,
            igstColKey: false,
            taxValColKey: false
        })
    }
    let values = [...this.state.selectedProductList];
    for(var i = 0 ; i < values.length ; i++){        
        this.calculateGross(i)
    }
  }

  getShippingDetails(ledger_id){
    // this.setState({
    //     loading: true
    // })
    var params = "?ledger=" + ledger_id;
    transactionService.getShippingDetails(params).then(res => {               
        console.log(res.data)
        this.setState({
            shippingDetails: res.data[0],
            shippingDetailsKey: false
        }, () => {
            if(this.state.shippingDetails.shipping_address != undefined){
                this.setState({
                    shippingDetailsKey: true,
                    ledgerStateCode: this.state.shippingDetails.state,
                    billing_address: this.state.shippingDetails['billing_address'][0],
                    shipping_address: this.state.shippingDetails['shipping_address'][0],
                    // loading: false
                }, () => {
                    // console.log(this.state.ledgerStateCode)
                    this.getColKey();
                })
            }
            else {
                this.setState({
                    ledgerStateCode: this.state.shippingDetails.state,
                    // loading: false
                }, () => {
                    // console.log(this.state.ledgerStateCode)
                    this.getColKey();
                })
            }
        })
    })
  }

  getTransactionParameters(){
    var params = "?tran_type=" + this.state.tran_type;
    transactionService.getTransactionParameters(params).then(res => {
        this.setState({
            transactionParameters: res.data[0]
        }, () => {
            if(this.state.transactionParameters.auto_no_status == 1){
                this.setState({
                    invoiceNumber: 'Auto'
                });
            }
            if(this.state.transactionParameters.auto_date == 1){
                this.setState({
                    date: moment().format('DD/MM/YYYY')
                })
            }

            if(this.state.transactionParameters.auto_no_status != 1) {
                this.refs.invoiceNumber.focus();
            } 
            if(this.state.transactionParameters.auto_date != 1) {
                this.refs.date.focus();
            }
            if(this.state.transactionParameters.auto_no_status == 1 && this.state.transactionParameters.auto_date == 1) {
                this.refs.debtorsValue.focus();
            }
        })        
        console.log(res.data)
    })
  }

  getSalesManList(){
    transactionService.getSalesManList().then(res => {
        var salesManData = [];
        res.data.forEach(x => {
            var d = {
                value: x.id,
                label: x.value,
                data: x
            }
            salesManData.push(d)
        })
        
        this.setState({
            allSalesManList: salesManData
        })               
        console.log(res.data)
    })
  }

  salesManOnChange = (selectedOption) => {
    this.setState({ salesManValue: selectedOption}, () => {
        console.log(this.state.salesManValue)
        this.refs.others.focus();
    })    
  }

  getProductList(){
    transactionService.getProductList().then(res => {        
        var productData = [];
        res.data.forEach(x => {
            var d = {
                value: x.id,
                // label: `${x.name} (${x.sku}) [Stock: ${x.stock}]`,
                label: x.name,
                data: x
            }
            productData.push(d)
        })
        
        this.setState({
            allProductList: productData
        })        
        console.log(res.data)
        // 
        if(this.state.transaction_id != ''){
            this.getTransactionDataById(this.state.transaction_id)
        }
    })
  }

  getdiscountList(){
    transactionService.getdiscountList().then(res => {       
        var expenseData = [];
        res.data.forEach(x => {
            var d = {
                value: x.id,
                label: x.ladger_name,
                data: x
            }
            expenseData.push(d)
        })
        
        this.setState({
            allExpenseList: expenseData
        })               
        console.log(res.data)
    })
  }

  checkValue = (str, max) =>{
    if (str.charAt(0) !== '0' || str == '00') {
      var num = parseInt(str);
      if (isNaN(num) || num <= 0 || num > max) num = 1;
      str = num > parseInt(max.toString().charAt(0)) && num.toString().length == 1 ? '0' + num : num.toString();
    };
    return str;
  }

  onDateChange = (e) =>{
    if(this.state.transactionParameters.auto_no_status != 1 && this.state.invoiceNumber == ''){
        toast.error(`Please Enter Invoice no.`, {
            position: "top-right",
            autoClose: 1000,
            hideProgressBar: false,
            closeOnClick: true,
            pauseOnHover: true,
            draggable: true
        });
    }
    else{
        var input = e.target.value;
        if (/\D\/$/.test(input)) input = input.substr(0, input.length - 3);
        var values = input.split('/').map(function(v) {
          return v.replace(/\D/g, '')
        });
        if (values[0]) values[0] = this.checkValue(values[0], 31);
        if (values[1]) values[1] = this.checkValue(values[1], 12);
        if(values[0] > '28' && values[1] == '02'){
            values[1] = '03'
        }
        
        if(values[2] != undefined){            
            if(values[1] <= this.state.financial_year.to.month){
                if(values[2].length == 1 ){
                    values[2] = this.state.financial_year.to.year
                }
            }
            else if(values[1] >= this.state.financial_year.form.month){
                if(values[2].length == 1 ){
                    values[2] = this.state.financial_year.form.year
                }
            }           
                    
        }
        // console.log(values[2])
        var output = values.map(function(v, i) {
          return v.length == 2 && i < 2 ? v + ' / ' : v;
        }); 
        console.log(output)       
        this.setState({date: output.join('').substr(0, 14)}, function(){
        //   console.log(this.state.date)
        }); 
    }
    
  }

  getFormSettingData = (data) =>{
    this.setState({formSettingData: data}, () => {
        console.log(this.state.formSettingData)
    })
    
  }

//   open() {
//     this.setState({
//       modalKey: true,
//     });
//   }

//   close() {
//     this.setState({
//       modalKey: false,
//     });
//   }

  getModalKey = (key) => {
    this.setState({
        modalKey: key
    })
  }

  getGodownBatchModalKey = (key) => {
    this.setState({
        modalGodownBatchKey: key
    })
  }
  
  getGodownModalKey = (key) => {
    this.setState({
        modalGodownKey: key
    })
  } 

  expenseFocus = (event) => {  
    if(event.key === 'Enter') {
        this.refs.expense.focus()
    }
  }

  productSearchFocus = (event) => {  
    if(event.key === 'Enter') {
        this.refs.productValue.focus()
    }
  }

  notesFocus = (event) => {  
    if(event.key === 'Enter') {
        this.refs.notes.focus()
    }
  }

  productOnChange = (selectedOption) => {    
    if(this.state.salesValue == ''){        
        toast.error(`Please Select ${this.state.ledgerSalesTitle}`, {
            position: "top-right",
            autoClose: 1000,
            hideProgressBar: false,
            closeOnClick: true,
            pauseOnHover: true,
            draggable: true
        });
    }
    else{
        this.setState({ productValue: selectedOption}, () => {
            if(this.state.selectedProductList.length == 0){
                let values = {...this.state.companyInfo};
                values['state'] = selectedOption[0]['data']['billingStateId']
                this.setState({companyInfo: values}, () => {
                    this.getColKey();
                })
            }            
            this.createProductRow()
        })
    }  
    
  }

  createProductRow = () => {
    let values = [...this.state.productValue];
    values.forEach(x => {
        // console.log(x)        
        var selectIndex = this.state.selectedProductList.findIndex(y => y.id == x.value);
        if(selectIndex == -1){
            var params = "?unit_id=" + x.data.id;
            transactionService.getComplexUnitById(params).then(res => {
                var allUnits = res.data;
                const options = []
                allUnits.forEach(z => {
                    var d = {
                        value: z.id,
                        label: z.value
                    }
                    options.push(d)
                })
                if(options.length == 0){
                    var d = {
                        value: x.data.productUnitId,
                        label: x.data.productUnitName
                    }
                    options.push(d)
                }

                var godownData = [];               
                var params = "?pid=" + x.data.id;
                transactionService.getGodownListById(params).then(res => {                    
                    res.data.forEach(x => {
                        // console.log(x)
                        var d = {
                            value: x.id,
                            label: x.value,
                            data: x
                        }
                        godownData.push(d)
                    })
                })

                var prodData = {
                    id: x.data.id,
                    name: x.data.name,
                    sku: x.data.sku,
                    description: x.data.shortDescription,
                    units: options,
                    unit: options[0],
                    godownValue: '',
                    ProductAllGodownList: godownData,
                    batchValue: '',
                    productGodownBatchData: [
                        {
                            godownValue: '',
                            allGodownList: [],
                            batchValue: '',
                            allBatchList: [],
                            qty: 0,
                            rate: +x.data.productSalesPrice,
                            grossTotal: +x.data.productSalesPrice
                        }
                    ],
                    qty: 0,
                    stock: x.data.stock,
                    rate: +x.data.productSalesPrice,
                    discount: '',
                    grossTotal: 0,
                    cgst: 0,
                    sgst: 0,
                    igst: 0,
                    cess: 0,
                    cessRate: 0,
                    taxValue: 0,
                    total: 0,
                    detailsData: x.data
                }
                this.state.selectedProductList.push(prodData)
                this.setState({
                    selectedProductList: this.state.selectedProductList
                }, () => {
                    var index = this.state.selectedProductList.findIndex(k => k.id == x.value);
                    this.calculateGross(index);
                    if(this.state.formSettingData.productDescReadOnly == 1) {
                        this.refs['unit'+index].focus();
                    } else {
                        this.refs['description'+index].focus();
                    }
                })
            }) 
        }
        
    })

    let selectedValues = [...this.state.selectedProductList];
    selectedValues.forEach(m => {
        var selectIndex = this.state.productValue.findIndex(n => n.value == m.id);
        if(selectIndex == -1){
            var removalIndex = this.state.selectedProductList.findIndex(o => o.id == m.id);
            if(removalIndex != -1){
                this.state.selectedProductList.splice(removalIndex, 1)
                this.setState({
                    selectedProductList: this.state.selectedProductList
                }, () => {
                    this.calculateSumValue()
                })
            }
        }
    })
    // this.buildRefKey()
    // setTimeout(function(){ $(".wqdescription").focus(); }, 1000);
  }

  removeProductRow = (data) => {       
    var index = this.state.productValue.findIndex(x => x.value == data.id)    
    if(index != -1){
        let values = [...this.state.productValue];
        values.splice(index, 1)
        this.setState({
            productValue: values
        })
    }
    var selectIndex = this.state.selectedProductList.findIndex(y => y.id == data.id);
    if(selectIndex != -1){
        let values = [...this.state.selectedProductList];
        values.splice(selectIndex, 1)
        this.setState({
            selectedProductList: values
        }, () => {
            this.buildRefKey()
            this.calculateSumValue()
            this.refs.productValue.focus();
        })
    }
        
  }

  expenseOnChange = (selectedOption) => {    
    if(this.state.selectedProductList.length == 0){
        toast.error(`Please Select Product`, {
            position: "top-right",
            autoClose: 1000,
            hideProgressBar: false,
            closeOnClick: true,
            pauseOnHover: true,
            draggable: true
        });
    }
    else{
        this.setState({ expense: selectedOption}, () => {
            this.createExpenseRow()
        })
    }
    
  }

  createExpenseRow = () => {
    let values = [...this.state.expense];
    values.forEach(x => {        
        var selectIndex = this.state.selectedExpenseList.findIndex(y => y.id == x.value);
        if(selectIndex == -1){
            var expenseData = {
                id: x.value,
                ladger_name: x.label,
                price: '',
                data: x.data
            }
            this.state.selectedExpenseList.push(expenseData)
            this.setState({
                selectedExpenseList: this.state.selectedExpenseList
            })
        }
    })
    let selectedValues = [...this.state.selectedExpenseList];
    selectedValues.forEach(y => {
        var selectIndex = this.state.expense.findIndex(z => z.value == y.id);
        if(selectIndex == -1){
            var removalIndex = this.state.selectedExpenseList.findIndex(m => m.id == y.id);
            if(removalIndex != -1){
                this.state.selectedExpenseList.splice(removalIndex, 1)
                this.setState({
                    selectedExpenseList: this.state.selectedExpenseList
                })
            }
        }
    })
    this.buildRefKey()
    this.calculateSumValue()
    setTimeout(function(){ $(".wqexpense").focus(); }, 500);
  }

  removeExpenseRow = (data) => {       
    var index = this.state.expense.findIndex(x => x.value == data.id)    
    if(index != -1){
        let values = [...this.state.expense];
        values.splice(index, 1)
        this.setState({
            expense: values
        })
    }
    var selectIndex = this.state.selectedExpenseList.findIndex(y => y.id == data.id);
    if(selectIndex != -1){
        let values = [...this.state.selectedExpenseList];
        values.splice(selectIndex, 1)
        this.setState({
            selectedExpenseList: values
        }, () => {
            this.buildRefKey()
            this.calculateSumValue()
            this.refs.expense.focus();
        })
    }
        
  }


  descriptionOnChange = (i, event) => {    
    let values = [...this.state.selectedProductList];
    values[i]['description'] = event.target.value;
    this.setState({ values });    
  }

  descriptionOnBlur = (i, event) => {  
    let values = [...this.state.selectedProductList];
    values[i]['isFocus'] = false;
    this.setState({ values });    
  }
  descriptionOnFocus = (i, event) => { 
    let values = [...this.state.selectedProductList];
    values[i]['isFocus'] = true;
    this.setState({ values });    
  }
  
  quantityOnChange = (i, event) => {
    //const re = RegExp(/^[0-9]+(\.[0-9][0-9]?)*$/);
    //console.log("===>>>",i);
    //console.log("===>>>",event.target.value);
    // if (event.target.value === '' || re.test(event.target.value)) {
    if (event.target.value != '') {
        if(this.state.transactionParameters.batch_status == 1 && this.state.transactionParameters.godown_status == 1){
            this.quantityRateOnBlur(i)
        }
        else if(this.state.transactionParameters.batch_status == 0 && this.state.transactionParameters.godown_status == 1){
            this.quantityRateOnBlur(i)
        }
        else if(this.state.transactionParameters.batch_status == 1 && this.state.transactionParameters.godown_status == 0){
            this.quantityRateOnBlur(i)
        }
        else{
            let values = [...this.state.selectedProductList];
            var qty_val = event.target.value
            var available_stock = values[i]['stock'] - qty_val;
            if(available_stock >= 0){
                values[i]['qty'] = qty_val
            }
            this.setState({ values }, () => {
                this.calculateGross(i);
            });
        }
    }   
        
        
  }
  calculateAvgQtyRate = (index) => {
    let values = [...this.state.selectedProductList];
    var qtySum = 0;
    var grossTotalSum = 0;
    var rateAvg = 0;    
    for(var i = 0 ; i < values[index]['productGodownBatchData'].length ; i++){
        if(+values[index]['productGodownBatchData'][i]['qty'] > 0){
            qtySum += +values[index]['productGodownBatchData'][i]['qty'];
        }        
        grossTotalSum += +values[index]['productGodownBatchData'][i]['grossTotal'];
    }
    values[index]['qty'] = qtySum;    
    if(qtySum > 0){
        rateAvg = parseFloat((grossTotalSum/qtySum).toFixed(2));
    }    
    values[index]['rate'] = rateAvg;       
    this.setState({ values }, () => {
        this.calculateGross(index);
    });
  }
  quantityRateOnBlur = (i) => {
    var data = this.state.selectedProductList[i]
    console.log(data);
    if(data.detailsData.productBatchStatus == 1){
        this.setState({
            modalGodownBatchKey: true,
            modalGodownKey: false,
            productIndex: i
        });
    }
    else{
        this.setState({
            modalGodownBatchKey: false,
            modalGodownKey: true,
            productIndex: i
        });
    }
    // var params = "?pid=" + data.id;
    // transactionService.getGodownListById(params).then(res => {
    //     var godownData = [];
    //     res.data.forEach(x => {
    //         // console.log(x)
    //         var d = {
    //             value: x.id,
    //             label: x.value,
    //             data: x
    //         }
    //         godownData.push(d)
    //     })
        
    //     this.setState({
    //         allGodownList: godownData
    //     }, () => {
    //         if(data.detailsData.productBatchStatus == 1){
    //             this.setState({
    //                 modalGodownBatchKey: true,
    //                 modalGodownKey: false,
    //                 productIndex: i
    //             });
    //         }
    //         else{
    //             this.setState({
    //                 modalGodownBatchKey: false,
    //                 modalGodownKey: true,
    //                 productIndex: i
    //             });
    //         }
    //     }) 
    // })
  }
  quantityRateOnBlurChange = (i) => {
    if(this.state.transactionParameters.batch_status == 1 && this.state.transactionParameters.godown_status == 1){
        this.quantityRateOnBlur(i)
    }
    else if(this.state.transactionParameters.batch_status == 0 && this.state.transactionParameters.godown_status == 1){
        this.quantityRateOnBlur(i)
    }
    else if(this.state.transactionParameters.batch_status == 1 && this.state.transactionParameters.godown_status == 0){
        this.quantityRateOnBlur(i)
    }       
        
  }

  unitOnChange = (i, selectedOption) => {
    let values = [...this.state.selectedProductList];
    values[i]['unit'] = selectedOption;
    this.setState({ values });
    this.refs['qty'+i].focus();
    // this.refs.qty0.focus();
  }
  rateOnChange = (i, event) => {
    // const re = RegExp(/^[0-9]+(\.[0-9][0-9]?)*$/);
    // if (event.target.value === '' || re.test(event.target.value)) {
    if (event.target.value != '') {
        if(this.state.transactionParameters.batch_status == 1 && this.state.transactionParameters.godown_status == 1){
            this.quantityRateOnBlur(i)
        }
        else if(this.state.transactionParameters.batch_status == 0 && this.state.transactionParameters.godown_status == 1){
            this.quantityRateOnBlur(i)
        }
        else if(this.state.transactionParameters.batch_status == 1 && this.state.transactionParameters.godown_status == 0){
            this.quantityRateOnBlur(i)
        }
        else{
            let values = [...this.state.selectedProductList];
            values[i]['rate'] = event.target.value;
            this.setState({ values }, () => {
                this.calculateGross(i);
            });
        } 
    }
       
        
  }

  discountOnChange = (i, event) => {
    // const re = RegExp(/^[0-9]+(\.[0-9][0-9]?)*$/);
    // if (event.target.value === '' || re.test(event.target.value)) {
    if (event.target.value != '') {
        let values = [...this.state.selectedProductList];
        values[i]['discount'] = event.target.value;
        this.setState({ values }, () => {
            this.calculateGross(i);
        }); 
        // this.refs.productValue.focus();
    }    
        
  }

  expensesChange = (i,event) => {   
    // var regex = RegExp(/^([+-]){0,1}([0-9])*$/);
    // if(regex.test(event.target.value)){        
    //     let values = [...this.state.selectedExpenseList];
    //     values[i]['price'] = event.target.value;
    //     this.setState({ values }, () => {
    //         this.calculateSumValue();
    //     });
    // }
    // const re = RegExp(/^[0-9]+(\.[0-9][0-9]?)*$/);
    // const re = RegExp(/^([+-]){0,1}([0-9])*$/);
    // if (event.target.value === '' || re.test(event.target.value)) {
    if (event.target.value != '') {
        let values = [...this.state.selectedExpenseList];
        values[i]['price'] = event.target.value;
        this.setState({ values }, () => {
            this.calculateSumValue();
        });
        // this.refs.expense.focus();
    }
           
  }

  notesChange = (event) => {
    this.setState({ notes:  event.target.value}, () => {
        this.calculateSumValue();
    });    
  }

  othersChange = (event) => {
    this.setState({ others:  event.target.value}, () => {
        this.calculateSumValue();
    });    
  }

  termsConditionsChange = (event) => {
    this.setState({ termsConditions:  event.target.value}, () => {
        this.calculateSumValue();
    });    
  }

  calculateGross = (i) => {
    let values = [...this.state.selectedProductList];
    values[i]['igst'] = 0;
    values[i]['cgst'] = 0;
    values[i]['sgst'] = 0;
    values[i]['cess'] = 0;
    values[i]['cessRate'] = 0;
    values[i]['grossTotal'] = 0;
    values[i]['taxValue'] = 0;
    values[i]['total'] = 0;
    var data = values[i]['detailsData'];    
    var rate = +values[i]['rate']
    var qty = +values[i]['qty']
    if(this.state.companyInfo.type == "1" && rate > 0 && qty > 0){
        if(this.state.companyInfo.state == this.state.ledgerStateCode){
            if(data.tax[0]['gst_type'] == 0){
                values[i]['cgst'] = +(data.tax[0].gst[0]['tax']/2);
                values[i]['sgst'] = +(data.tax[0].gst[0]['tax']/2);
            }
            else if(data.tax[0]['gst_type'] == 1){
                var taxVal = 0;
                for(var j = 0 ; j < data.tax[0].gst.length ; j++){
                    if(rate >= +data.tax[0].gst[j]['form_item_rate'] && rate < +data.tax[0].gst[j]['to_item_rate']){
                        taxVal = +(data.tax[0].gst[j]['tax'])
                    }
                    else if(rate >= data.tax[0].gst[j]['form_item_rate'] && data.tax[0].gst[j]['to_item_rate'].toLowerCase() == 'rest'){
                        taxVal = +(data.tax[0].gst[j]['tax'])
                    }
                }
                values[i]['cgst'] = taxVal/2;
                values[i]['sgst'] = taxVal/2;
            }
            if(data.tax[0]['cess_status'] == 0){
                values[i]['cess'] = 0;
            }
            else if(data.tax[0]['cess_status'] == 1){
                if(data.tax[0]['cess_type'] == 0){
                    values[i]['cess'] = +data.tax[0]['cess_value']
                }
                else if(data.tax[0]['cess_type'] == 1){
                    values[i]['cessRate'] = +data.tax[0]['cess_rate']
                }
                else if(data.tax[0]['cess_type'] == 2){
                    values[i]['cess'] = +data.tax[0]['cess_value']
                    values[i]['cessRate'] = +data.tax[0]['cess_rate']
                }
            }
        }
        else{
            if(data.tax[0]['gst_type'] == 0){
                values[i]['igst'] = +data.tax[0].gst[0]['tax'];
            }
            else if(data.tax[0]['gst_type'] == 1){
                var taxVal = 0;
                for(var k = 0 ; k < data.tax[0].gst.length ; k++){
                    if(rate >= +data.tax[0].gst[k]['form_item_rate'] && rate < +data.tax[0].gst[k]['to_item_rate']){
                        taxVal = +data.tax[0].gst[k]['tax']
                    }
                    else if(rate >= +data.tax[0].gst[k]['form_item_rate'] && data.tax[0].gst[k]['to_item_rate'].toLowerCase() == 'rest'){
                        taxVal = +data.tax[0].gst[k]['tax']
                    }
                }
                values[i]['igst'] = taxVal;
            }
            if(data.tax[0]['cess_status'] == 0){
                values[i]['cess'] = 0;
            }
            else if(data.tax[0]['cess_status'] == 1){
                if(data.tax[0]['cess_type'] == 0){
                    values[i]['cess'] = +data.tax[0]['cess_value']
                }
                else if(data.tax[0]['cess_type'] == 1){
                    values[i]['cessRate'] = +data.tax[0]['cess_rate']
                }
                else if(data.tax[0]['cess_type'] == 2){
                    values[i]['cess'] = +data.tax[0]['cess_value']
                    values[i]['cessRate'] = +data.tax[0]['cess_rate']
                }
            }
        }
        
    }
    else{
        values[i]['cgst'] = 0;
        values[i]['sgst'] = 0;
        values[i]['igst'] = 0;
        values[i]['cess'] = 0;
        values[i]['cessRate'] = 0;
    }
    this.setState({values}, () => {
        let setValues = [...this.state.selectedProductList];
        var qty = +setValues[i]['qty']
        var rate = +setValues[i]['rate']
        var discount = +setValues[i]['discount']
        var grossTotal = 0;
        grossTotal = (qty*rate) - ((qty*rate*discount)/100)
        var igst = +setValues[i]['igst']
        var cgst = +setValues[i]['cgst']
        var sgst = +setValues[i]['sgst']
        var cess = +setValues[i]['cess']
        var cessRate = +setValues[i]['cessRate']
        var total = 0;
        var igstTotal = 0;
        var cgstTotal = 0;
        var sgstTotal = 0;
        var cessTotal = 0;
        var cessRateTotal = 0;
        var taxTotal = 0;
        
        igstTotal = (grossTotal*igst)/100
        cgstTotal = (grossTotal*cgst)/100
        sgstTotal = (grossTotal*sgst)/100
        cessTotal = (grossTotal*cess)/100
        cessRateTotal = qty*cessRate
        taxTotal = igstTotal + cgstTotal + sgstTotal + cessTotal + cessRateTotal
        total = grossTotal + taxTotal
        setValues[i]['grossTotal'] = parseFloat(grossTotal.toFixed(2))
        setValues[i]['taxValue'] = parseFloat(taxTotal.toFixed(2))
        setValues[i]['total'] = parseFloat(total.toFixed(2))
        this.setState({ setValues }, () => {
            this.calculateSumValue()
        });
    })
    
    
  }

  calculateSumValue = () =>{
    let newSelectedProdValues = [...this.state.selectedProductList];
    let newExpenseValues = [...this.state.selectedExpenseList];    
    var prodGrossSum = 0;
    var expenseSum = 0;    
    newSelectedProdValues.forEach(x => {
        prodGrossSum += x.grossTotal
    })
    if(newExpenseValues.length > 0){
        newExpenseValues.forEach(y => {
            var price = +y.price
            if(y.data.include_assessable == 1){
                for(var i = 0; i < newSelectedProdValues.length ; i++){
                    var dis = 0;
                    var rate = newSelectedProdValues[i]['rate'];                
                    var qty = +newSelectedProdValues[i]['qty'];
                    var grossTotal = newSelectedProdValues[i]['grossTotal'];
                    var igst = +newSelectedProdValues[i]['igst']
                    var cgst = +newSelectedProdValues[i]['cgst']
                    var sgst = +newSelectedProdValues[i]['sgst']
                    var cess = +newSelectedProdValues[i]['cess']
                    var cessRate = +newSelectedProdValues[i]['cessRate']
                    var igstTotal = 0;
                    var cgstTotal = 0;
                    var sgstTotal = 0;
                    var cessTotal = 0;
                    var cessRateTotal = 0;
                    var taxTotal = 0;
                    if(qty > 0 && rate > 0){
                        dis = (price/prodGrossSum)*grossTotal
                        console.log(dis)                                        
                        igstTotal = ((grossTotal+dis)*igst)/100
                        cgstTotal = ((grossTotal+dis)*cgst)/100
                        sgstTotal = ((grossTotal+dis)*sgst)/100
                        cessTotal = ((grossTotal+dis)*cess)/100
                        cessRateTotal = qty*cessRate
                        taxTotal = igstTotal + cgstTotal + sgstTotal + cessTotal + cessRateTotal
                        newSelectedProdValues[i]['taxValue'] = parseFloat(taxTotal.toFixed(2))
                        newSelectedProdValues[i]['total'] = parseFloat(((grossTotal+dis) + taxTotal).toFixed(2))
                    }
                    
                }
                this.setState({ newSelectedProdValues })
            }
            else if(y.data.include_assessable == 0){
                expenseSum += price
            }
            
        })
    }
    else{
        for(var i = 0; i < newSelectedProdValues.length ; i++){
            var qty = +newSelectedProdValues[i]['qty']
            var grossTotal = newSelectedProdValues[i]['grossTotal'];
            var igst = +newSelectedProdValues[i]['igst']
            var cgst = +newSelectedProdValues[i]['cgst']
            var sgst = +newSelectedProdValues[i]['sgst']
            var cess = +newSelectedProdValues[i]['cess']
            var cessRate = +newSelectedProdValues[i]['cessRate']
            var igstTotal = 0;
            var cgstTotal = 0;
            var sgstTotal = 0;
            var cessTotal = 0;
            var cessRateTotal = 0;
            var taxTotal = 0;                
            igstTotal = (grossTotal*igst)/100
            cgstTotal = (grossTotal*cgst)/100
            sgstTotal = (grossTotal*sgst)/100
            cessTotal = (grossTotal*cess)/100
            cessRateTotal = qty*cessRate
            taxTotal = igstTotal + cgstTotal + sgstTotal + cessTotal + cessRateTotal
            newSelectedProdValues[i]['taxValue'] = parseFloat(taxTotal.toFixed(2))
            newSelectedProdValues[i]['total'] = parseFloat((grossTotal + taxTotal).toFixed(2))
            
        }
        this.setState({ newSelectedProdValues })
    }

    var expenseIndex = newExpenseValues.findIndex(k => k.data.include_assessable == 1)
    if(expenseIndex == -1){
        for(var i = 0; i < newSelectedProdValues.length ; i++){
            var qty = +newSelectedProdValues[i]['qty']
            var grossTotal = newSelectedProdValues[i]['grossTotal'];
            var igst = +newSelectedProdValues[i]['igst']
            var cgst = +newSelectedProdValues[i]['cgst']
            var sgst = +newSelectedProdValues[i]['sgst']
            var cess = +newSelectedProdValues[i]['cess']
            var cessRate = +newSelectedProdValues[i]['cessRate']
            var igstTotal = 0;
            var cgstTotal = 0;
            var sgstTotal = 0;
            var cessTotal = 0;
            var cessRateTotal = 0;
            var taxTotal = 0;                
            igstTotal = (grossTotal*igst)/100
            cgstTotal = (grossTotal*cgst)/100
            sgstTotal = (grossTotal*sgst)/100
            cessTotal = (grossTotal*cess)/100
            cessRateTotal = qty*cessRate
            taxTotal = igstTotal + cgstTotal + sgstTotal + cessTotal + cessRateTotal
            newSelectedProdValues[i]['taxValue'] = parseFloat(taxTotal.toFixed(2))
            newSelectedProdValues[i]['total'] = parseFloat((grossTotal + taxTotal).toFixed(2))
            
        }
        this.setState({ newSelectedProdValues })
    }
    var prodSum = 0;
    var grossSum = 0;
    var taxSum = 0;
    var total = 0;
    let newSelectedProdValuesWithAssessable = [...this.state.selectedProductList];
    newSelectedProdValuesWithAssessable.forEach(x => {
        prodSum += +x.total
        grossSum += +x.grossTotal
        taxSum += +x.taxValue
    })
    total = prodSum + expenseSum
    this.setState({selectedItemTotalValue: parseFloat(prodSum.toFixed(2))})
    this.setState({selectedItemGrossValue: parseFloat(grossSum.toFixed(2))})
    this.setState({selectedItemTaxValue: parseFloat(taxSum.toFixed(2))})
    this.setState({selectedProductTotalValue: parseFloat(total.toFixed(2))})
  }

  invoiceNumberChange = (event) => {
    this.setState({ invoiceNumber:  event.target.value});
  }

  advanceBillNameChange = (event) => {
    this.setState({ advanceBillName:  event.target.value});
  }

  recurringChange = (event) => {
    this.setState({ recurring:  event.target.value});
  }

  refNoNumberChange = (event) => {
    this.setState({ refNo:  event.target.value});
  }

  refDateChange = (e) => {
    var input = e.target.value;
    if (/\D\/$/.test(input)) input = input.substr(0, input.length - 3);
    var values = input.split('/').map(function(v) {
      return v.replace(/\D/g, '')
    });
    if (values[0]) values[0] = this.checkValue(values[0], 31);
    if (values[1]) values[1] = this.checkValue(values[1], 12);
     
    if(values[0] > '28' && values[1] == '02'){
        values[1] = '03'
    }    
    var output = values.map(function(v, i) {
        return v.length == 2 && i < 2 ? v + ' / ' : v;
    });
    this.setState({refDate: output.join('').substr(0, 14)}, function(){
    //   console.log(this.state.refDate)
    });
  }
  checkValidation = () => {
    if(this.state.transactionParameters.auto_no_status != 1 && this.state.invoiceNumber == ''){
        toast.error(`Please Enter Invoice no.`, {
            position: "top-right",
            autoClose: 1000,
            hideProgressBar: false,
            closeOnClick: true,
            pauseOnHover: true,
            draggable: true
        });
        return false;
    }
    else if(this.state.date == ''){
        toast.error(`Please Enter Date`, {
            position: "top-right",
            autoClose: 1000,
            hideProgressBar: false,
            closeOnClick: true,
            pauseOnHover: true,
            draggable: true
        });
        return false;
    }
    else if(this.state.date != '' && this.state.date.replace(/\s/g, "").length < 10){
        toast.error(`Please Enter Valid Date(DD/MM/YYYY)`, {
            position: "top-right",
            autoClose: 1000,
            hideProgressBar: false,
            closeOnClick: true,
            pauseOnHover: true,
            draggable: true
        });
        return false;
    }
    else if(this.state.debtorsValue == ''){
        toast.error(`Please Select ${this.state.ledgerDebtorsTitle}`, {
            position: "top-right",
            autoClose: 1000,
            hideProgressBar: false,
            closeOnClick: true,
            pauseOnHover: true,
            draggable: true
        });
        return false;
    }
    else if(this.state.salesValue == ''){
        toast.error(`Please Select ${this.state.ledgerSalesTitle}`, {
            position: "top-right",
            autoClose: 1000,
            hideProgressBar: false,
            closeOnClick: true,
            pauseOnHover: true,
            draggable: true
        });
        return false;
    }
    else if(this.state.refDate != '' && this.state.refDate.replace(/\s/g, "").length < 10){
        toast.error(`Please Enter Valid Ref Date(DD/MM/YYYY)`, {
            position: "top-right",
            autoClose: 1000,
            hideProgressBar: false,
            closeOnClick: true,
            pauseOnHover: true,
            draggable: true
        });
        return false;
    }
    else if(this.state.selectedProductList.length == 0){
        toast.error(`Please Select Product`, {
            position: "top-right",
            autoClose: 1000,
            hideProgressBar: false,
            closeOnClick: true,
            pauseOnHover: true,
            draggable: true
        });
        return false;
    }
    // else if(this.state.selectedProductList.length > 0){
    //     for(var i = 0; i < this.state.selectedProductList.length ; i++){
    //         if(this.state.selectedProductList[i]['qty'] == 0){
    //             toast.error(`Please Enter Quantity of ${i+1} Product`, {
    //                 position: "top-right",
    //                 autoClose: 1000,
    //                 hideProgressBar: false,
    //                 closeOnClick: true,
    //                 pauseOnHover: true,
    //                 draggable: true
    //             });
    //             return false;
    //         }
    //         else if(this.state.selectedProductList[i]['rate'] == 0){
    //             toast.error(`Please Enter Rate of ${i+1} Product`, {
    //                 position: "top-right",
    //                 autoClose: 1000,
    //                 hideProgressBar: false,
    //                 closeOnClick: true,
    //                 pauseOnHover: true,
    //                 draggable: true
    //             });
    //             return false;
    //         }
    //         else if(this.state.selectedProductList[i]['productGodownBatchData'].length > 0){
    //             for(var j = 0; j < this.state.selectedProductList[i]['productGodownBatchData'].length ; j++){
    //                 if(this.state.selectedProductList[i]['productGodownBatchData'][j]['godownValue'] == ''){
    //                     toast.error(`Please Select Godown on ${j+1} Row of ${i+1} Product`, {
    //                         position: "top-right",
    //                         autoClose: 1000,
    //                         hideProgressBar: false,
    //                         closeOnClick: true,
    //                         pauseOnHover: true,
    //                         draggable: true
    //                     });
    //                     return false;
    //                 }
    //                 else if(this.state.selectedProductList[i]['detailsData']['productBatchStatus'] == 1 && this.state.selectedProductList[i]['productGodownBatchData'][j]['batchValue'] == ''){
    //                     toast.error(`Please Select Batch on ${j+1} Row of ${i+1} Product`, {
    //                         position: "top-right",
    //                         autoClose: 1000,
    //                         hideProgressBar: false,
    //                         closeOnClick: true,
    //                         pauseOnHover: true,
    //                         draggable: true
    //                     });
    //                     return false;
    //                 }
    //                 else if(this.state.selectedProductList[i]['productGodownBatchData'][j]['qty'] == 0){
    //                     toast.error(`Please Enter Quantity on ${j+1} Row of ${i+1} Product`, {
    //                         position: "top-right",
    //                         autoClose: 1000,
    //                         hideProgressBar: false,
    //                         closeOnClick: true,
    //                         pauseOnHover: true,
    //                         draggable: true
    //                     });
    //                     return false;
    //                 }
    //                 else if(this.state.selectedProductList[i]['productGodownBatchData'][j]['rate'] == 0){
    //                     toast.error(`Please Enter Rate on ${j+1} Row of ${i+1} Product`, {
    //                         position: "top-right",
    //                         autoClose: 1000,
    //                         hideProgressBar: false,
    //                         closeOnClick: true,
    //                         pauseOnHover: true,
    //                         draggable: true
    //                     });
    //                     return false;
    //                 }
    //                 else if( i == this.state.selectedProductList.length - 1 && j == this.state.selectedProductList[i]['productGodownBatchData'].length - 1){
    //                     return true;
    //                 }                    
    //             }
    //         }            
    //     }
    //     console.log("this.state.selectedProductList ==>>",this.state.selectedProductList);
    //     console.log("Not OK");
    // }
    else{
        console.log("OK");
        return true;
    }
  }
  formSubmit = () => {
    if(this.state.action == 'Edit') {
        if(this.checkValidation()) {
            console.log(this.state.editdetails);
            var wqedit = [];
            var data = {
                tr_branch_id: this.state.userDetails['branch_id'],
                select_branch_id: this.state.userDetails['branch_id'],
                companyTypeStatus: this.state.userDetails['company_type'],
                bill_details_status: '',
                date_form: this.state.date.replace(/\s/g, ""),
                advance_bill_name: this.state.advanceBillName,
                shipping_id: '',
                credit_days: '',
                newRefCall: 0,
                select_currency: this.state.formSettingData['selectedCurrency'],
                select_currency_unit: this.state.formSettingData['selectedCurrencyUnit'],
                base_currency: this.state.formSettingData['base_currency'],
                base_currency_unit: this.state.formSettingData['base_currency_unit'],
                postdated: this.state.formSettingData['postDatedEntryOption'],
                form_submission: this.state.formSettingData['formSubmissionOption'],
                
                igst_status: "0", 
                entry_type: this.state.tran_type, 
                bank_id: this.state.transactionParameters.bank_id,
                parent_id: this.state.transactionParameters.parent_id,
                action: 'e',
                godown_status: this.state.transactionParameters.godown_status,
                batch_status: this.state.transactionParameters.batch_status, 
                voucher: this.state.editdetails.voucher,
                voucher_id: this.state.editdetails.voucher_id,
                transaction_type_id: this.state.editdetails.transaction_type_id,
                auto_no_status: this.state.editdetails.auto_no_status,
                auto_date: this.state.transactionParameters.auto_date,
                recurring: this.state.transactionParameters.recurring,
                ledger_code_status: this.state.transactionParameters.ledger_code_status, 
                entry: [],
                order: [],
                courier: [],
                sales_man_dateils: [],
                entry_details: [],
                productData: [],
            }
            if(this.state.shippingDetailsKey){
                data.shipping_id = this.state.shipping_address['Sh_id']
                data.credit_days = this.state.shippingDetails.credit_days[0]['LL_creditDays']
            }
            if(this.state.bankValue){
                data.bank_id = this.state.bankValue['value']
            }

            var data1 = {
                edit_entry_id: this.state.transaction_id,
                entry_no: this.state.invoiceNumber,
                create_date: this.state.date,
                voucher_no: this.state.refNo,
                voucher_date: this.state.refDate,
                tax_total: '',
                product_grand_total: '',
                grand_total: '',
            }

            var data2 = {
                edit_order_id: this.state.editdetails.order.id,
                notes: this.state.notes,
                terms_and_conditions: this.state.termsConditions, 
                tax_total: '',
                product_grand_total: '',
                grand_total: '',
            }

            var data3 = {
                despatch_doc_no: '',
                despatch_through: '',
                courier_gstn: '',
                destination: '',
                bill_lr_rr: '',
                bill_lr_rr_date: '',
                motor_vehicle_no: '',
                vehicle_type: '',
                transportation_mode: '',
            }
            if(this.state.despatchValue){
                data3.despatch_through = this.state.despatchDetails.despatch_through
                data3.despatch_doc_no = this.state.despatchDetails.despatch_doc_no
                data3.courier_gstn = this.state.despatchDetails.courier_gstn
                data3.destination = this.state.despatchDetails.destination
                data3.bill_lr_rr = this.state.despatchDetails.bill_lr_rr
                data3.bill_lr_rr_date = this.state.despatchDetails.bill_lr_rr_date
                data3.motor_vehicle_no = this.state.despatchDetails.motor_vehicle_no
                data3.vehicle_type = this.state.despatchDetails.vehicle_type
                data3.transportation_mode = this.state.despatchDetails.transportation_mode
            }
            data.courier.push(data3);

            var data4 = {
                id: '',
                sales_man_name: '', 
            }
            if(this.state.salesManValue){
                data4.sales_man_name = this.state.salesManValue['label'];
                data4.id = this.state.salesManValue['value']
            }
            data.sales_man_dateils.push(data4);

            var sumTaxVal = 0;
            var sumGrandVal = 0;
            this.state.selectedProductList.forEach(x => {
                var ProdData = {
                    entry_product_id: x.entry_product_id, 
                    product_id: x.detailsData.product_id,
                    stock_id: x.detailsData.id,
                    cess_status_col: x.detailsData.tax[0]['cess_status'],
                    product_description: x.description,
                    product_unit: x.unit.label,
                    product_unit_hidden_id: x.detailsData.productUnitId,
                    product_complex_unit_hidden_id: x.unit.value,
                    product_quantity: x.qty,
                    product_price: x.rate,
                    product_discount: x.discount,
                    total_price_per_prod: x.qty*x.rate,
                    gross_total_price_per_prod: x.grossTotal,
                    cgst_tax_percent: x.cgst,
                    sgst_tax_percent: x.sgst,
                    igst_tax_percent: x.igst,
                    cess_percent: x.cess,
                    cess_item_qty: x.cessRate,
                    cgst_tax_value: (x.grossTotal*x.cgst)/100,
                    sgst_tax_value: (x.grossTotal*x.sgst)/100,
                    igst_tax_value: (x.grossTotal*x.igst)/100,
                    cess_value: (x.grossTotal*x.cess)/100,
                    cess_item_qty_value: x.qty*x.cessRate,
                    total_price_per_prod_with_tax: x.total,
                    discount_value_hidden: (x.qty*x.rate*x.discount)/100,
                    discount_percent_hidden: '',
                    batchGodown: []
                }
                x.productGodownBatchData.forEach(y => {
                    var batchGodownData = null;
                    if(x.detailsData.productBatchStatus == 1){
                        batchGodownData = {
                            batch_godown_id: y.godownValue.value,
                            batch_godown_name: y.godownValue.data.godown_name,
                            batch_no: y.batchValue.data.batch_no,
                            batch_id: y.batchValue.data.id,
                            manufact_date: y.batchValue.data.manufact_date,
                            exp_type_id: y.batchValue.data.exp_type,
                            exp_type: '',
                            exp_days: '',
                            batch_qty: y.qty,
                            batch_rate: y.rate,
                            batch_value: y.grossTotal,
                            product_stock_id: x.detailsData.id,
                            batch_base_unit_id: x.detailsData.productUnitId,
                            batch_complex_unit_id: x.unit.value,
                            productBatchStatus: 1
        
                        }
                    }
                    else if(x.detailsData.productBatchStatus == 0){
                        batchGodownData = {
                            batch_godown_id: y.godownValue.value,
                            batch_godown_name: y.godownValue.data.godown_name,
                            godown_qty: y.qty,
                            godown_rate: y.rate,
                            godown_value: y.grossTotal,
                            product_stock_id: x.detailsData.id,
                            batch_base_unit_id: x.detailsData.productUnitId,
                            batch_complex_unit_id: x.unit.value,
                            productBatchStatus: 0
        
                        }
                    }
                    ProdData.batchGodown.push(batchGodownData)        
                })

                data.productData.push(ProdData)
                sumTaxVal += x.taxValue
                sumGrandVal += x.total
            })
            data1.tax_total = sumTaxVal;
            data1.product_grand_total = sumGrandVal;
            data1.grand_total = this.state.selectedProductTotalValue;
            data2.tax_total = sumTaxVal;
            data2.product_grand_total = sumGrandVal;
            data2.grand_total = this.state.selectedProductTotalValue;
            data.entry.push(data1);
            data.order.push(data2);
            
            if(this.state.debtorsValue){
                data.bill_details_status = this.state.debtorsValue.data.bill_details_status;
                var data6 = {
                    edit_entry_details_id: this.state.debtorsValue_entry_id,
                    ladger_name: this.state.debtorsValue.label,
                    ledger_id: this.state.debtorsValue.value,
                    account: this.state.debtorsValue.data.account
                }
                data.entry_details.push(data6);
            }
            if(this.state.salesValue){
                var data6 = {
                    edit_entry_details_id: this.state.salesValue_entry_id,
                    ladger_name: this.state.salesValue.label,
                    ledger_id: this.state.salesValue.value,
                    account: this.state.salesValue.data.account
                }
                data.entry_details.push(data6);
            }
            if(this.state.igstColKey) {
                data.igst_status = "1"
                // var data6 = {
                //     edit_entry_details_id: this.state.salesValue_entry_id,
                //     ladger_name: this.state.salesValue.label,
                //     ledger_id: this.state.salesValue.value,
                //     account: this.state.salesValue.data.account
                // }
                // data.entry_details.push(data6);
            }
            if(this.state.selectedExpenseList.length > 0){
                this.state.selectedExpenseList.forEach(z => {
                    var data6 = {
                        edit_entry_details_id: z.expenseValue_entry_id,
                        ladger_name: `${z.data.ladger_name} (${z.data.ledger_code}) [Cur. Bal. ${z.data.current_closing_balance} - ${z.data.account}]`,
                        ledger_id: z.id,
                        account: z.data.account,
                        balance: z.price
                    }
                    data.entry_details.push(data6)
                })
            }

            wqedit.push(data);
            console.log(wqedit)
            console.log(JSON.stringify(wqedit))

            return false;

            if(this.state.debtorsValue.data.bill_details_status == 1){
                var current_credit_limit = parseFloat(((+this.state.shippingDetails.credit_days[0]['LL_creditLimit']) - (+this.state.debtorsValue.data.current_closing_balance)).toFixed(2))
                console.log(current_credit_limit)
                // if(data.netTotal < current_credit_limit){
                    transactionService.salesUpdate(data).then(res => {
                        console.log(res)
                        // if(res.status == 200){
                        //     var url = '/sales/5';
                        //     if(this.props.location.pathname != url){
                        //         this.props.history.push(url)
                        //     }
                        // }
                    })
                // }
                // else{
                //     toast.error(`You exceed your credit limit`, {
                //         position: "top-right",
                //         autoClose: 1000,
                //         hideProgressBar: false,
                //         closeOnClick: true,
                //         pauseOnHover: true,
                //         draggable: true
                //     });
                // }
            } else {
                transactionService.salesUpdate(data).then(res => {
                    console.log(res)
                    // if(res.status == 200){
                    //     var url = '/sales/5';
                    //     if(this.props.location.pathname != url){
                    //         this.props.history.push(url)
                    //     }
                    // }
                })
            }
        }

    } else {

        var data = {
            igst_status: 0,
            godown_status: this.state.transactionParameters.godown_status,
            batch_status: this.state.transactionParameters.batch_status,
            tr_branch_id: this.state.userDetails['branch_id'],
            select_branch_id: this.state.userDetails['branch_id'],
            companyTypeStatus: this.state.userDetails['company_type'],
            entry_id: this.state.tran_type,
            // edit_entry_id: this.state.transaction_id,
            bank_id: this.state.transactionParameters.bank_id,
            entry_number: this.state.invoiceNumber,
            bill_details_status: '',
            despatch_doc_no: '',//blank
            despatch_through: '',//blank
            courier_gstn: '',//blank
            destination: '',//blank
            bill_lr_rr: '',//blank
            bill_lr_rr_date: '',//blank
            motor_vehicle_no: '',//blank
            vehicle_type: '',//blank
            transportation_mode: '',//blank
            date_form: this.state.date.replace(/\s/g, ""),
            ledgerData: [],
            in_ledger_state: '',//blank
            in_ledger_country: '',//blank
            advance_bill_name: this.state.advanceBillName,
            voucher_no: this.state.refNo,
            voucher_date: this.state.refDate.replace(/\s/g, ""),
            branch_entry_no: '',//blank
            sales_person: '',
            sales_person_id: '',
            shipping_id: '',
            credit_days: '',
            productData: [],
            tax_value: '',
            product_grand_total: '',
            netTotal: '',
            notes: this.state.notes,
            terms_and_conditions:this.state.termsConditions,
            newRefCall: 0,
            entry_type: this.state.tran_type,
            parent_id: '',//sub voucher 
            select_currency: this.state.formSettingData['selectedCurrency'],
            select_currency_unit: this.state.formSettingData['selectedCurrencyUnit'],
            base_currency: this.state.formSettingData['base_currency'],
            base_currency_unit: this.state.formSettingData['base_currency_unit'],
            postdated: this.state.formSettingData['postDatedEntryOption'],
            type_service_product: '',
            reverse_entry: '',
            form_submission: this.state.formSettingData['formSubmissionOption']
        }
        if(this.state.debtorsValue){
            data.bill_details_status = this.state.debtorsValue.data.bill_details_status;
        }
        if(this.state.salesManValue){
            data.sales_person = this.state.salesManValue['label'];
            data.sales_person_id = this.state.salesManValue['value']
        }
        if(this.state.despatchValue){
            data.despatch_through = this.state.despatchDetails.despatch_through
            data.despatch_doc_no = this.state.despatchDetails.despatch_doc_no
            data.courier_gstn = this.state.despatchDetails.courier_gstn
            data.destination = this.state.despatchDetails.destination
            data.bill_lr_rr = this.state.despatchDetails.bill_lr_rr
            data.bill_lr_rr_date = this.state.despatchDetails.bill_lr_rr_date
            data.motor_vehicle_no = this.state.despatchDetails.motor_vehicle_no
            data.vehicle_type = this.state.despatchDetails.vehicle_type
            data.transportation_mode = this.state.despatchDetails.transportation_mode
        }
        if(this.state.bankValue){
            data.bank_id = this.state.bankValue['value']
        }
        if(this.checkValidation()){
            if(this.state.igstColKey){
                data.igst_status = 1
            }
            if(this.state.shippingDetailsKey){
                data.shipping_id = this.state.shipping_address['Sh_id']
                data.credit_days = this.state.shippingDetails.credit_days[0]['LL_creditDays']
            }
            
            var sumTaxVal = 0;
            var sumGrandVal = 0;
            console.log(this.state.selectedProductList)        
            this.state.selectedProductList.forEach(x => {
                var ProdData = {
                    product_id: x.detailsData.product_id,
                    stock_id: x.detailsData.id,
                    cess_status_col: x.detailsData.tax[0]['cess_status'],
                    product_description: x.description,
                    product_unit: x.unit.label,
                    product_unit_hidden_id: x.detailsData.productUnitId,
                    product_complex_unit_hidden_id: x.unit.value,
                    product_quantity: x.qty,
                    product_price: x.rate,
                    product_discount: x.discount,
                    total_price_per_prod: x.qty*x.rate,
                    gross_total_price_per_prod: x.grossTotal,
                    cgst_tax_percent: x.cgst,
                    sgst_tax_percent: x.sgst,
                    igst_tax_percent: x.igst,
                    cess_percent: x.cess,
                    cess_item_qty: x.cessRate,
                    cgst_tax_value: (x.grossTotal*x.cgst)/100,
                    sgst_tax_value: (x.grossTotal*x.sgst)/100,
                    igst_tax_value: (x.grossTotal*x.igst)/100,
                    cess_value: (x.grossTotal*x.cess)/100,
                    cess_item_qty_value: x.qty*x.cessRate,
                    total_price_per_prod_with_tax: x.total,
                    discount_value_hidden: (x.qty*x.rate*x.discount)/100,
                    discount_percent_hidden: '',
                    batchGodown: []
                }
                x.productGodownBatchData.forEach(y => {
                    var batchGodownData = null;
                    if(x.detailsData.productBatchStatus == 1){
                        batchGodownData = {
                            batch_godown_id: y.godownValue.value,
                            batch_godown_name: y.godownValue.data.godown_name,
                            batch_no: y.batchValue.data.batch_no,
                            batch_id: y.batchValue.data.id,
                            manufact_date: y.batchValue.data.manufact_date,
                            exp_type_id: y.batchValue.data.exp_type,
                            exp_type: '',
                            exp_days: '',
                            batch_qty: y.qty,
                            batch_rate: y.rate,
                            batch_value: y.grossTotal,
                            product_stock_id: x.detailsData.id,
                            batch_base_unit_id: x.detailsData.productUnitId,
                            batch_complex_unit_id: x.unit.value,
                            productBatchStatus: 1
        
                        }
                    }
                    else if(x.detailsData.productBatchStatus == 0){
                        batchGodownData = {
                            batch_godown_id: y.godownValue.value,
                            batch_godown_name: y.godownValue.data.godown_name,
                            godown_qty: y.qty,
                            godown_rate: y.rate,
                            godown_value: y.grossTotal,
                            product_stock_id: x.detailsData.id,
                            batch_base_unit_id: x.detailsData.productUnitId,
                            batch_complex_unit_id: x.unit.value,
                            productBatchStatus: 0
        
                        }
                    }
                    ProdData.batchGodown.push(batchGodownData)
                    
                })
                data.productData.push(ProdData)
                sumTaxVal += x.taxValue
                sumGrandVal += x.total
            })
            data.tax_value = sumTaxVal;
            data.product_grand_total = sumGrandVal;
            data.netTotal = this.state.selectedProductTotalValue;
            if(this.state.debtorsValue){
                var ldgrData = {
                    tr_ledger: this.state.debtorsValue.label,
                    tr_ledger_id: this.state.debtorsValue.value,
                    tr_type: this.state.debtorsValue.data.account,
                    price: ''
                }
                data.ledgerData.push(ldgrData)
            }
            if(this.state.salesValue){
                var ldgrData = {
                    tr_ledger: this.state.salesValue.label,
                    tr_ledger_id: this.state.salesValue.value,
                    tr_type: this.state.salesValue.data.account,
                    price: ''
                }
                data.ledgerData.push(ldgrData)
            }
            if(this.state.selectedExpenseList.length > 0){
                this.state.selectedExpenseList.forEach(z => {
                    var ldgrData = {
                        tr_ledger: `${z.data.ladger_name} (${z.data.ledger_code}) [Cur. Bal. ${z.data.current_closing_balance} - ${z.data.account}]`,
                        tr_ledger_id: z.id,
                        tr_type: z.data.account,
                        price: z.price
                    }
                    data.ledgerData.push(ldgrData)
                })
            }
            console.log(data)
            console.log(JSON.stringify(data))
            // return false;

            if(this.state.debtorsValue.data.bill_details_status == 1){
                // eslint-disable-next-line no-redeclare
                var current_credit_limit = parseFloat(((+this.state.shippingDetails.credit_days[0]['LL_creditLimit']) - (+this.state.debtorsValue.data.current_closing_balance)).toFixed(2))
                console.log(current_credit_limit)
                console.log(data.netTotal)
                if(data.netTotal < current_credit_limit){
                    if(this.state.tran_type == '5') {
                        transactionService.transactionSubmit(data).then(res => {
                            console.log(res)
                            if(res.status == 200){
                                var url = '/sales/5';
                                if(this.state.formSettingData['formSubmissionOption'] == 1) {
                                    window.location.reload();
                                } else if (this.state.formSettingData['formSubmissionOption'] == 2) {
                                    this.props.history.push(url)
                                } else {
                                    //
                                }
                                // if(this.props.location.pathname != url){
                                //     this.props.history.push(url)
                                // }
                            }
                        })
                    } else if(this.state.tran_type == '6') {
                        transactionService.transactionSubmit(data).then(res => {
                            console.log(res)
                            // if(res.status == 200){
                            //     var url = '/purchase/6';
                            //     if(this.props.location.pathname != url){
                            //         this.props.history.push(url)
                            //     }
                            // }
                        })
                    } else {
                        console.log("oops");
                    }
                }
                else{
                    toast.error(`You exceed your credit limit`, {
                        position: "top-right",
                        autoClose: 1000,
                        hideProgressBar: false,
                        closeOnClick: true,
                        pauseOnHover: true,
                        draggable: true
                    });
                }
            } else{
                if(this.state.tran_type == '5') {
                    transactionService.transactionSubmit(data).then(res => {
                        console.log(res)
                        if(res.status == 200){
                            var url = '/sales/5';
                            if(this.props.location.pathname != url){
                                this.props.history.push(url)
                            }
                        }
                    })
                } else if(this.state.tran_type == '6') {
                    transactionService.transactionSubmit(data).then(res => {
                        console.log(res)
                        if(res.status == 200){
                            var url = '/purchase/6';
                            if(this.props.location.pathname != url){
                                this.props.history.push(url)
                            }
                        }
                    })
                } else {
                    console.log("oops");
                }
            }
            
        }  
    }  
    
  }

  openDespatchDetails() {
    if(this.state.debtorsValue != ''){
        if(this.state.debtorsValue.data.bill_details_status == 1){
            this.setState({
                despatchDetailsModalKey: true,
                changeShippingAddressModalKey: false,
                bankDetailsModalKey: false
            });
        }   
    }     
  }

  openChangeShippingAddress() {
    if(this.state.shippingDetailsKey){
        this.setState({
            changeShippingAddressModalKey: true,
            despatchDetailsModalKey: false,
            bankDetailsModalKey: false
        });
    }    
  }

  getDespatchDetailsModalKey = (key) => {
    this.setState({
        despatchDetailsModalKey: key
    })
  }

  getDespatchOnChange = (selectedOption) => {
    this.getDespatchDetailsByCourierId(selectedOption['value'])
    this.setState({
        despatchValue: selectedOption
    }, function() {
        setTimeout(function(){ $(".despatch_doc_no").focus(); }, 500);
    })
  }

  getDespatchDetailsByCourierId(id){
    transactionService.getDespatchDetailsByCourierId(id).then(res => {
        console.log(res.data)
        let values = {...this.state.despatchDetails}
        values['despatch_through'] = res.data['despatch_through']
        values['despatch_doc_no'] = res.data['despatch_doc_no']
        values['courier_gstn'] = res.data['courier_gstn']
        values['destination'] = res.data['destination']
        values['bill_lr_rr'] = res.data['bill_lr_rr']
        values['bill_lr_rr_date'] = moment(res.data['bill_lr_rr_date']).format('DD/MM/YYYY')
        values['motor_vehicle_no'] = res.data['motor_vehicle_no']
        values['vehicle_type'] = ''
        values['transportation_mode'] = ''
        this.setState({despatchDetails: values})
    })
  }

  despatchFieldOnChange = (field, value) =>{
    let values = {...this.state.despatchDetails}
    values[field] = value
    this.setState({despatchDetails: values})
  }

  getAllDespatchList(){
    transactionService.getDespatchDetails().then(res => {            
        var despatchData = [];
        res.data.forEach(x => {
            var d = {
                value: x.id,
                label: x.despatch_through,
                data: x
            }
            despatchData.push(d)
        })            
        this.setState({
            allDespatchList: despatchData
        })
        console.log("despatchData=>",res.data)
    })
  }

  getChangeShippingAddressModalKey = (key) => {
    this.setState({
        changeShippingAddressModalKey: key
    })
  }

  getContactOnChange = (selectedOption) => {    
    this.setState({
        contactValue: selectedOption
    })
  }
  
  getShippingOnChange = (selectedOption) => {
    this.setState({
        shippingValue: selectedOption
    })
  }

  getShippingAndLedgerState = (data) => {
    if(data != null){
        let values = {...this.state.shipping_address};
        this.setState({
            shippingAddressByContact: data,
            ledgerStateCode: data['state']
        }, () => {
            this.getColKey();
            console.log(this.state.shippingAddressByContact)
            values['Sh_id'] = this.state.shippingAddressByContact['id']
            values['Sh_companyName'] = this.state.shippingAddressByContact['company_name']
            values['Sh_address'] = this.state.shippingAddressByContact['address']
            values['Sh_city'] = this.state.shippingAddressByContact['city']
            values['Sh_zip'] = this.state.shippingAddressByContact['zip']
            values['Sh_state'] = this.state.shippingAddressByContact['state_name']
            values['Sh_country'] = this.state.shippingAddressByContact['country_name']
            values['Sh_tax'] = this.state.shippingAddressByContact['sales_tax_no']
            this.setState({shipping_address: values})
        })
    }
    else{
        this.setState({
            ledgerStateCode: this.state.shippingDetails.state,
            shipping_address: this.state.shippingDetails['shipping_address'][0]
        }, () => {
            this.getColKey();
        })
    }   
    
  }


  getAllContactsList(){
    transactionService.getAllContactsList().then(res => {            
        var contactData = [];
        res.data.forEach(x => {
            var d = {
                value: x.id,
                label: x.company_name,
                data: x
            }
            contactData.push(d)
        })            
        this.setState({
            allContactList: contactData
        })
        console.log(res.data)
    })
  }
  

  getShippingAddressByContactId(id){
    transactionService.getShippingAddressByContactId(id).then(res => {
        console.log(res.data)
        let values = {...this.state.shipping_address};
        this.setState({
            shippingAddressByContact: res.data[0],
            ledgerStateCode: res.data[0]['state']
        }, () => {
            this.getColKey();
            console.log(this.state.shippingAddressByContact)
            values['Sh_id'] = this.state.shippingAddressByContact['id']
            values['Sh_companyName'] = this.state.shippingAddressByContact['company_name']
            values['Sh_address'] = this.state.shippingAddressByContact['address']
            values['Sh_city'] = this.state.shippingAddressByContact['city']
            values['Sh_zip'] = this.state.shippingAddressByContact['zip']
            values['Sh_state'] = this.state.shippingAddressByContact['state_name']
            values['Sh_country'] = this.state.shippingAddressByContact['country_name']
            values['Sh_tax'] = this.state.shippingAddressByContact['sales_tax_no']
            this.setState({shipping_address: values})
        })
    })
  }


  openBankDetails() {
    if(this.state.preferencesDetails['eway_bill'] == 1){
        this.setState({
            bankDetailsModalKey: true,
            despatchDetailsModalKey: false,
            changeShippingAddressModalKey: false,
        });
    }    
  }

  getBankDetailsModalKey = (key) => {
    this.setState({
        bankDetailsModalKey: key
    })
  }

  getBankListForTransaction(){
    transactionService.getBankListForTransaction().then(res => {            
        var bankData = [];
        res.data[0]['banks'].forEach(x => {
            var d = {
                value: x.id,
                label: x.bank_name,
                data: x
            }
            bankData.push(d)
        })            
        this.setState({
            allBankList: bankData
        })
        console.log(res.data)
    })
  }

  getBankOnChange = (selectedOption) => {
    //console.log(selectedOption);
    this.setState({
        bankValue: selectedOption,
        wqbankName: selectedOption.label
    })
  }

  render() {
    const handlers = {
        'bankDetails': this.openBankDetails.bind(this),
        'despatchDetails': this.openDespatchDetails.bind(this),
        'changeShippingAddress': this.openChangeShippingAddress.bind(this),
    };    
    
    const selectedProductItems = this.state.selectedProductList.map((item, i) =>
        <tr  key={i}>
            <td><a onClick={() => this.removeProductRow(item)}><i className='fa fa-trash-o text-danger'></i></a></td>
            <td>{item.name}</td>
            <td className={this.state.formSettingData.productDescReadOnly == 1 ? 'hidden td-input' : 'td-input'}>
                <input readOnly={this.state.formSettingData.productDescReadOnly == 1 ? true : false} ref={`description${i}`} name={`description${i}`} value={item.description} type="text" className={item.isFocus ? 'form-control prod-desc isFocus wqdescription' : 'form-control prod-desc wqdescription'} placeholder="product description"
                onChange={this.descriptionOnChange.bind(this,i)}
                onBlur={this.descriptionOnBlur.bind(this,i)}
                onFocus={this.descriptionOnFocus.bind(this,i)}
                />
            </td>
            <td className="td-input">
                <Select
                    value={item.unit}
                    onChange={this.unitOnChange.bind(this,i)}
                    options={item.units} 
                    styles={customStyles}
                    placeholder="Select Unit"
                    ref={`unit${i}`} name={`unit${i}`}
                    components = {
                        {
                            DropdownIndicator: () => null,
                            IndicatorSeparator: () => null
                        }
                    }
                />
            </td>
            <td className="td-input">
                <input ref={`qty${i}`} name={`qty${i}`} value={item.qty} type="text"  className="form-control" placeholder="Qty"
                onChange={this.quantityOnChange.bind(this,i)}
                onBlur={() => this.quantityRateOnBlurChange(i)}
                style={{width: '80px'}}
                />
            </td>
            <td className="td-input">
                <input ref={`rate${i}`} name={`rate${i}`} value={item.rate} type="text"  className="form-control text-right" placeholder="Rate"
                onChange={this.rateOnChange.bind(this,i)}
                onBlur={() => this.quantityRateOnBlurChange(i)}
                style={{width: '80px'}}
                />
            </td>
            <td className={this.state.formSettingData.productDiscReadOnly == 1 ? 'hidden td-input' : 'td-input'}>
                <input readOnly={this.state.formSettingData.productDiscReadOnly == 1 ? true : false} ref={`discount${i}`} name={`discount${i}`} value={item.discount} type="text"  className="form-control text-right wqdiscount" placeholder="Discount"
                onChange={this.discountOnChange.bind(this,i)} onKeyDown={this.productSearchFocus.bind(this)}
                style={{width: '80px'}}
                />
            </td>
            <td className="text-right">{item.grossTotal}</td>
            {
                this.state.cgstColKey && (
                    <td className="text-right">{item.cgst}</td>
                )
            }

            {
                this.state.sgstColKey && (
                    <td className="text-right">{item.sgst}</td>
                )
            }

            {
                this.state.igstColKey && (
                    <td className="text-right">{item.igst}</td>
                )
            }

            {
                this.state.cessColKey && (
                    <td className="text-right">
                    {
                        item.cess > 0 && item.cessRate > 0 && (
                            <small>
                                <span>Price label: {item.cess}(%)</span><br/>
                                <span>Item label: {item.cessRate}</span>
                            </small>
                        )
                    }
                    {
                        item.cess > 0 && item.cessRate == 0 && (
                            <small>
                                <span>Price label: {item.cess}(%)</span>
                            </small>
                        )
                    }
                    {
                        item.cess == 0 && item.cessRate > 0 && (
                            <small>
                                <span>Item label: {item.cessRate}</span>
                            </small>
                        )
                    }
                    </td>
                )
            }
            {
                this.state.taxValColKey && (
                    <td className="text-right">{item.taxValue}</td>
                )
            }
            
            <td className="text-right">{item.total}</td>
        </tr>
    )
    
    const selectedExpenseItems = this.state.selectedExpenseList.map((item, i) =>
        <tr style={{position: 'relative'}} key={i}>
            <td><a onClick={() => this.removeExpenseRow(item)}><i className='fa fa-trash-o text-danger'></i></a></td>
            <td colSpan="10" >{item.ladger_name}</td>
            <td colSpan="3" className="text-right">
                <input ref={`expense${i}`} name={`expense${i}`} value={item.price} type="text"  className="form-control text-right wqexpense" placeholder="Expenses"
                onChange={this.expensesChange.bind(this,i)} onKeyDown={this.expenseFocus.bind(this)}
                />
            </td>            
        </tr>
    )
    let data;
    if (this.state.loading) {
        data = <div className="loading"><div className="loading-spinner"></div></div>
    }
    else{
        data =  <section className="content">
            <div className="box clearfix">
                <div className="box-body">
                    <div className="container-fluidX">
                        <div className="row">
                            <div className="col-lg-3 col-sm-6">
                                <div className="form-group">
                                    <label>Invoice Number</label>
                                    {
                                        this.state.transactionParameters.auto_no_status == 1 && (
                                            <div className="clearfix">
                                                {this.state.invoiceNumber}
                                            </div>
                                        )
                                    }
                                    {
                                        this.state.transactionParameters.auto_no_status != 1 && (
                                            <input maxLength="16" ref="invoiceNumber" name="invoiceNumber" type="text" className="form-control" placeholder="Invoice Number" value={this.state.invoiceNumber} onChange={this.invoiceNumberChange.bind(this)}/>
                                        )
                                    }                                   
                                </div>
                            </div>
                            <div className="col-lg-3 col-sm-6">
                                <div className="form-group">
                                    <label>Date</label>
                                    {
                                        this.state.transactionParameters.auto_date == 1 && (
                                            <div className="clearfix">
                                                {this.state.date}
                                            </div>                                                
                                        )
                                    }
                                    {
                                        this.state.transactionParameters.auto_date != 1 && (
                                            <input ref="date" name="date" type='text' placeholder="DD/MM/YYYY" className='form-control' value={this.state.date} onChange={this.onDateChange.bind(this)} autoComplete="off"/>
                                        )
                                    }
                                    
                                </div>
                            </div>
                            <div className="col-lg-3 col-sm-6">
                                <div className="form-group">
                                    <label>{this.state.ledgerDebtorsTitle}</label>
                                    <Select
                                        value={this.state.debtorsValue}
                                        // menuIsOpen= {true}
                                        onChange={this.ledgerOnChange.bind(this)}
                                        options={this.state.allLedgerDebtorsList} 
                                        styles={customLedgerStyles}
                                        className="wqDebtorsSelect"
                                        placeholder={`Select ${this.state.ledgerDebtorsTitle}`}
                                        ref="debtorsValue" name="debtorsValue"
                                        components = {
                                            {
                                                DropdownIndicator: () => null,
                                                IndicatorSeparator: () => null
                                            }
                                        }
                                    />
                                </div>
                            </div>
                            <div className="col-lg-3 col-sm-6">
                                <div className="form-group">
                                    <label>{this.state.ledgerSalesTitle}</label>
                                    <Select
                                        value={this.state.salesValue}
                                        onChange={this.salesOnChange.bind(this)}
                                        options={this.state.allLedgerSalesList} 
                                        styles={customLedgerStyles}
                                        className="wqSalesSelect"
                                        placeholder={`Select ${this.state.ledgerSalesTitle}`}
                                        ref="salesValue" name="salesValue"
                                        components = {
                                            {
                                                DropdownIndicator: () => null,
                                                IndicatorSeparator: () => null
                                            }
                                        }
                                    />
                                </div>
                            </div>
                        </div>


                        <div className="row">
                            <div className="col-lg-3 col-sm-6">
                                <div className="form-group">
                                    <input maxLength="16" ref="advanceBillName" name="advanceBillName" type="text" className="form-control" placeholder="Advance Bill Name" value={this.state.advanceBillName} onChange={this.advanceBillNameChange.bind(this)}/>
                                </div>
                            </div>
                            <div className="col-lg-3 col-sm-6">
                                <div className="form-group">
                                    {
                                        this.state.transactionParameters.recurring == 1 && (
                                            <input maxLength="16" ref="recurring" name="recurring" type="text" className="form-control" placeholder="Recurring" value={this.state.recurring} onChange={this.recurringChange.bind(this)}/>
                                        )
                                    }
                                    
                                </div>
                            </div>
                            <div className="col-lg-3 col-sm-6">
                                <div className="form-group">
                                    <input maxLength="16" ref="refNo" name="refNo" type="text" className="form-control" placeholder="Ref. No." value={this.state.refNo} onChange={this.refNoNumberChange.bind(this)}/>
                                </div>
                            </div>
                            <div className="col-lg-3 col-sm-6">
                                <div className="form-group">
                                    <input ref="refDate" name="refDate" type="text" className="form-control" placeholder="Ref. Date" value={this.state.refDate} onChange={this.refDateChange.bind(this)}/>
                                </div>
                            </div>
                        </div>
                        {
                            this.state.shippingDetailsKey && (
                                <div className="row">
                                    <div className="col-lg-3 col-sm-6">
                                        <div className="form-group">
                                            <label>Billing Address</label><br/>
                                            <strong>{this.state.billing_address['Bi_companyName']}</strong><br/>
                                            {this.state.billing_address['Bi_address']}, {this.state.billing_address['Bi_city']} - {this.state.billing_address['Bi_zip']}<br/>
                                            {this.state.billing_address['Bi_state']}, {this.state.billing_address['Bi_country']}<br/>
                                            GSTIN: {this.state.billing_address['Bi_tax']}
                                        </div>
                                    </div>
                                    <div className="col-lg-3 col-sm-6">
                                        <div className="form-group">
                                            <label>Shipping Address</label><br/>
                                            <strong>{this.state.shipping_address['Sh_companyName']}</strong><br/>
                                            {this.state.shipping_address['Sh_address']}, {this.state.shipping_address['Sh_city']} - {this.state.shipping_address['Sh_zip']}<br/>
                                            {this.state.shipping_address['Sh_state']}, {this.state.shipping_address['Sh_country']}<br/>
                                            GSTIN: {this.state.shipping_address['Sh_tax']}
                                        </div>
                                    </div>
                                    <div className="col-lg-3 col-sm-6">
                                        <div className="form-group">
                                            <label>Credit days</label>
                                            <div className="clearfix">
                                                {this.state.shippingDetails.credit_days[0]['LL_creditDays']}
                                            </div>
                                        </div>
                                    </div>
                                    <div className="col-lg-3 col-sm-6">
                                        <div className="form-group">
                                            <label>Credit Limit</label>
                                            <div className="clearfix">
                                                {this.state.shippingDetails.credit_days[0]['LL_creditLimit']}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            )
                        }
                        <div className="row">
                            <div className="col-lg-3 col-sm-6">
                                <div className="form-group">
                                    <Select
                                        value={this.state.salesManValue}
                                        onChange={this.salesManOnChange.bind(this)}
                                        options={this.state.allSalesManList} 
                                        styles={customLedgerStyles}
                                        placeholder={`Select Sales Man`}
                                        ref="salesman" name="salesman"
                                        components = {
                                            {
                                                DropdownIndicator: () => null,
                                                IndicatorSeparator: () => null
                                            }
                                        }
                                    />
                                </div>
                            </div>
                            <div className="col-lg-3 col-sm-6">
                                <div className="form-group">
                                    {/* <input type="text" ref="others" name="others" className="form-control" placeholder="Others"/> */}
                                    <input ref="others" name="others" type="text" className="form-control wqothers" placeholder="Others" value={this.state.others} onChange={this.othersChange.bind(this)}/>
                                </div>
                            </div>
                            {
                                this.state.formSettingData.taxForDifferentExportCountryOption == 1 && (
                                    <div className="col-lg-3 col-sm-6">
                                        <div className="form-group">
                                            <label>{this.state.formSettingData.selectedCurrencyName} : {this.state.formSettingData.selectedCurrencyUnit}</label>
                                        </div>
                                    </div>
                                )
                            }
                            
                        </div>
                        <div className="row">
                            <div className="col-lg-12">
                                <div className="">
                                    <table className="table table-stripped" style={{position: 'relative'}}>
                                        <thead>
                                            <tr>
                                                <th width="20"></th>
                                                <th>Name</th>
                                                <th className={this.state.formSettingData.productDescReadOnly == 1 ? 'hidden th-input' : 'th-input'}>Description</th>
                                                <th>Unit</th>
                                                <th>Qty.</th>
                                                <th className="text-right">Rate</th>
                                                <th className={this.state.formSettingData.productDiscReadOnly == 1 ? 'hidden text-right' : 'text-right'}>Dis.(%)</th>
                                                <th className="text-right">Gross Total</th>
                                                {
                                                    this.state.cgstColKey && (
                                                        <th className="text-right">CGST(%)</th>
                                                    )
                                                }

                                                {
                                                    this.state.sgstColKey && (
                                                        <th className="text-right">SGST(%)</th>
                                                    )
                                                }

                                                {
                                                    this.state.igstColKey && (
                                                        <th className="text-right">IGST(%)</th>
                                                    )
                                                }

                                                {
                                                    this.state.cessColKey && (
                                                        <th className="text-right">CESS</th>
                                                    )
                                                }
                                                {
                                                    this.state.taxValColKey && (
                                                        <th className="text-right">Tax Value</th>
                                                    )
                                                }
                                                <th className="text-right">Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>                                                
                                            {selectedProductItems}
                                            <tr>
                                                <td colSpan="13" className="td-input">
                                                    <Select
                                                        value={this.state.productValue}
                                                        onChange={this.productOnChange.bind(this)}
                                                        onKeyDown={this.expenseFocus.bind(this)}
                                                        options={this.state.allProductList}
                                                        isMulti 
                                                        isClearable={false}
                                                        styles={customLedgerStyles}
                                                        placeholder="Search product..."
                                                        ref="productValue" name="productValue"
                                                        components = {
                                                            {
                                                                DropdownIndicator: () => null,
                                                                IndicatorSeparator: () => null
                                                            }
                                                        }
                                                    />                                                    
                                                </td>
                                            </tr>
                                            <tr className="bold">
                                                <td colSpan="4"></td>
                                                <td colSpan="2" className="text-right">Gross Total</td>
                                                <td colSpan="1" className="text-right">
                                                    {this.state.selectedItemGrossValue}
                                                </td>
                                                <td colSpan="2" className="text-right">Tax Total</td>
                                                <td colSpan="1" className="text-right">
                                                    {this.state.selectedItemTaxValue}
                                                </td>
                                                <td colSpan="2" className="text-right">Items Total</td>
                                                <td colSpan="1" className="text-right">
                                                    {this.state.selectedItemTotalValue}
                                                </td>
                                            </tr>
                                            {selectedExpenseItems}
                                            <tr>
                                                <td colSpan="13" className="td-input">
                                                    <Select
                                                        value={this.state.expense}
                                                        onChange={this.expenseOnChange.bind(this)}
                                                        onKeyDown={this.notesFocus.bind(this)}
                                                        options={this.state.allExpenseList}
                                                        isMulti 
                                                        styles={customLedgerStyles}
                                                        placeholder="Search expense..."
                                                        ref="expense" name="expense"
                                                        components = {
                                                            {
                                                                DropdownIndicator: () => null,
                                                                IndicatorSeparator: () => null
                                                            }
                                                        }
                                                    />
                                                </td>
                                            </tr>                                            
                                            <tr className="bold">
                                                <td colSpan="9"></td>
                                                <td colSpan="2" className="text-right">Net Total</td>
                                                <td colSpan="2" className="text-right">
                                                    {/* <input readOnly type="text" className="form-control text-right" value={this.state.selectedProductTotalValue} /> */}
                                                    {this.state.selectedProductTotalValue}
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div className="row">
                            <div className="col-lg-12">
                                <div className="form-group">
                                    &nbsp;
                                </div>
                                <div className="form-group">
                                    <label>Notes</label>
                                    <input ref="notes" name="notes" type="text" className="form-control" placeholder="Notes" value={this.state.notes} onChange={this.notesChange.bind(this)}/>
                                </div>
                                <div className="form-group">
                                    <label>Terms & Conditions</label>
                                    <input ref="termsConditions" name="termsConditions" type="text" className="form-control" placeholder="Terms & Conditions" value={this.state.termsConditions} onChange={this.termsConditionsChange.bind(this)}/>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div className="box-footer text-right">
                    <button ref="wqsalesForm" className="btn btn-primary" onClick={this.formSubmit}>Save</button>
                </div>
            </div>
        </section>
    }
    return (
      <React.Fragment>
        <HotKeys keyMap={keyMap} handlers={handlers}>
            <div className="content-wrapper">
                <Suspense  fallback={this.loading()}>
                    {
                        this.state.preferencesDetails != null && (
                            <Breadcrumb title={this.state.title} action={this.state.action} getFormSettingData={this.getFormSettingData} preferencesDetails={this.state.preferencesDetails}/>
                        )
                    }
                    
                </Suspense>
                {
                    this.state.despatchDetailsModalKey && (
                        <Suspense  fallback={this.loading()}>
                            <DespatchDetails getDespatchDetailsModalKey={this.getDespatchDetailsModalKey} allDespatchList={this.state.allDespatchList} despatchValue={this.state.despatchValue} getDespatchOnChange={this.getDespatchOnChange} despatchDetails={this.state.despatchDetails} despatchFieldOnChange={this.despatchFieldOnChange}/>
                        </Suspense>
                    )
                }
                {
                    this.state.changeShippingAddressModalKey && (
                        <Suspense  fallback={this.loading()}>
                            <ChangeShippingAddress getChangeShippingAddressModalKey={this.getChangeShippingAddressModalKey} contactValue={this.state.contactValue} allContactList={this.state.allContactList} shippingValue={this.state.shippingValue} getContactOnChange={this.getContactOnChange} getShippingOnChange={this.getShippingOnChange} getShippingAndLedgerState={this.getShippingAndLedgerState}/>
                        </Suspense>
                    )
                }
                {
                    this.state.bankDetailsModalKey && (
                        <Suspense  fallback={this.loading()}>
                            <BankDetails getBankDetailsModalKey={this.getBankDetailsModalKey} wqbankName={this.state.wqbankName} bankValue={this.state.bankValue} allBankList={this.state.allBankList} getBankOnChange={this.getBankOnChange}/>
                        </Suspense>
                    )
                }
                {
                    this.state.modalGodownBatchKey && (
                        <Suspense  fallback={this.loading()}>                            
                            <GodownBatch wqdescindex={this.state.formSettingData.productDiscReadOnly} productIndex={this.state.productIndex} selectedProductList={this.state.selectedProductList} getGodownBatchModalKey={this.getGodownBatchModalKey} calculateAvgQtyRate={this.calculateAvgQtyRate}/>
                        </Suspense>
                    )
                }
                {
                    this.state.modalGodownKey && (
                        <Suspense  fallback={this.loading()}> 
                            <Godown wqdescindex={this.state.formSettingData.productDiscReadOnly} productIndex={this.state.productIndex} selectedProductList={this.state.selectedProductList} getGodownModalKey={this.getGodownModalKey}  calculateAvgQtyRate={this.calculateAvgQtyRate} />
                        </Suspense>
                    )
                }
                {data}
            </div>
            <ToastContainer />           
        </HotKeys>
      </React.Fragment>
    );
  }
}
  
export default TransactionForm;