import global from '../global';
import axios from 'axios';

export function getLedgerDebtorsList(params){
    return axios.get(`${global.apiBaseUrl}ledgerDebtors${params}`)
}

export function getShippingDetails(params){
    return axios.get(`${global.apiBaseUrl}shippingDetails${params}`)
}

export function getLedgerSalesList(params){
    return axios.get(`${global.apiBaseUrl}ledgerSales${params}`)
}

export function getTransactionParameters(params){
    return axios.get(`${global.apiBaseUrl}transactionParameters${params}`)
}

export function getPreferencesDetails(){
    return axios.get(`${global.apiBaseUrl}preferences`)
}

export function getProductList(){
    return axios.get(`${global.apiBaseUrl}products`)
}

export function getProductDetails(params){
    return axios.get(`${global.apiBaseUrl}productDetails${params}`)
}

export function getdiscountList(){
    return axios.get(`${global.apiBaseUrl}ledgerRounding`)
}

export function getGodownListById(params){
    return axios.get(`${global.apiBaseUrl}godownListById${params}`)
}

export function getBatchByGodownIdAndProductId(params){
    return axios.get(`${global.apiBaseUrl}batchByGodownIdAndProductId${params}`)
}

export function getComplexUnitById(params){
    return axios.get(`${global.apiBaseUrl}complexUnitById${params}`)
}

export function getUserDetails(){
    return axios.get(`${global.apiBaseUrl}userDetails`)
}

export function transactionSubmit(data){
    return axios.post(`${global.apiBaseUrl}transactionSubmit`,data)
}

export function getTransactionDataById(params){
    return axios.get(`${global.apiBaseUrl}transactionForm${params}`)
}

export function getSalesManList(){
    return axios.get(`${global.apiBaseUrl}salesMan`)
}

export function getDespatchDetails(){
    return axios.get(`${global.apiBaseUrl}despatchDetails`)
}

export function getDespatchDetailsByCourierId(id){
    return axios.get(`${global.apiBaseUrl}despatchDetailsById?courier_id=${id}`)
}

export function getAllContactsList(){
    return axios.get(`${global.apiBaseUrl}allContacts`)
}

export function getShippingAddressByContactId(id){
    return axios.get(`${global.apiBaseUrl}shippingAddress?contact_id=${id}`)
}

export function getBankListForTransaction(){
    return axios.get(`${global.apiBaseUrl}banksForTransaction`)
}

export function getAllCurrency(){
    return axios.get(`${global.apiBaseUrl}getAllCurrency`)
}

export function getTransactionList(params){
    return axios.get(`${global.apiBaseUrl}transactionList${params}`)
}

export function salesUpdate(data){
    return axios.post(`${global.apiBaseUrl}transactionUpdate`,data)
}
// http://lab-6.sketchdemos.com/saas-product/access-saas/webservices/transactionUpdate