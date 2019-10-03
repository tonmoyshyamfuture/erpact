import React, { Component } from 'react';
import {BootstrapTable, TableHeaderColumn , } from 'react-bootstrap-table';
import 'react-bootstrap-table/dist/react-bootstrap-table-all.min.css';
import * as transactionService from '../../core/services/TransactionService';
import './TransactionList.css';

class TransactionList extends Component {
    constructor(props) {
        super(props);
        this.state = {
            title: '',
            transactionDataList: []
        }
    }

    componentDidMount = () => {
        var location = this.props.match.params.name;
        var current_page = location.split('-');
        var page_title = ''
        if(current_page.length > 1){
            page_title = current_page[0] + ' ' + current_page[1]
        }
        else{
            page_title = current_page[0]
        }
        page_title = page_title.toLowerCase().replace(/\b[a-z]/g, (letter) => {
            return letter.toUpperCase();
        });
        document.title = "ACT | " + page_title;
        this.setState({
            title: page_title,
            transactionDataList: []
        }, () => {
            this.getTransactionList()
        })
    }

    componentDidUpdate = (prevProps) => {
        if (prevProps.location.pathname !== this.props.location.pathname) {
            var location = this.props.match.params.name;
            var current_page = location.split('-');
            var page_title = ''
            if(current_page.length > 1){
                page_title = current_page[0] + ' ' + current_page[1]
            }
            else{
                page_title = current_page[0]
            }
            page_title = page_title.toLowerCase().replace(/\b[a-z]/g, (letter) => {
                return letter.toUpperCase();
            });
            document.title = "ACT | " + page_title;
            this.setState({
                title: page_title,
                transactionDataList: []
            }, () => {
                this.getTransactionList()
            })
        }
    }
    
    getTransactionList(){
        var params = "?name=" + this.props.match.params.name + '&id=' + this.props.match.params.tran_type;
        transactionService.getTransactionList(params).then(res => {
            this.setState({
                transactionDataList: res.data['all_entries']
            }, () => {
                console.log(this.state.transactionDataList)
            })        
            console.log(res.data)
        })
    }
    
    editItem(data){
        var url = '/transaction/' + this.props.match.params.name + '-edit' + '/' + this.props.match.params.tran_type + '/' + data.id;
        if(this.props.location.pathname != url){
            this.props.history.push(url)
        }
    }

    addItem(){
        var url = '/transaction/' + this.props.match.params.name + '-add' + '/' + this.props.match.params.tran_type;
        if(this.props.location.pathname != url){
            this.props.history.push(url)
        }
    }

    buttonFormatter(cell,data){
        return (
            <a 
               onClick={() => 
               this.editItem(data)}
            >
            <i className="fa fa-pencil-square-o" aria-hidden="true"></i>
            </a>
         )
    }

    render() {
        
        return (            
            <div className="content-wrapper">
                <section className="content-header">
                    <div className="row">
                        <div className="col-xs-5">
                            <h1><i className="fa fa-list"></i> {this.state.title} </h1>
                        </div>
                        <div className="col-xs-7 text-right">
                            <div className="btn-wrapper">                                
                                <a className="btn btn-sm btn-primary" onClick={() => 
                                this.addItem()}>Add</a>
                            </div>
                        </div>
                    </div>
                </section>
                <section className="clearfix">
                    <div className="col-md-12">
                        <ol className="breadcrumb">
                            <li><a href="#"><i className="fa fa-dashboard"></i> Transactions</a></li>
                            <li className="active">{this.state.title} List</li>
                        </ol>
                    </div>
                </section>
                <section className="content">
                    <div className="box">
                        <div className="box-header hidden">
                            <h3>header</h3>
                        </div>
                        <div className="box-body box-table">
                            <div className="col-md-12 list-data-table">
                                <BootstrapTable data={this.state.transactionDataList} striped hover pagination={ true } search={ true }>
                                    <TableHeaderColumn isKey dataField='create_date' dataSort={ true }>Date</TableHeaderColumn>
                                    <TableHeaderColumn dataField='entry_no' dataSort={ true }>Number</TableHeaderColumn>
                                    <TableHeaderColumn dataField='type' dataSort={ true }>Type</TableHeaderColumn>
                                    <TableHeaderColumn dataField='cr_amount' dataSort={ true }>Amount</TableHeaderColumn>
                                    <TableHeaderColumn dataField='ledger_ids_by_accounts' dataSort={ true }>Ledger</TableHeaderColumn>
                                    <TableHeaderColumn dataField='action' export={ false } dataFormat={this.buttonFormatter.bind(this)}>Action</TableHeaderColumn>
                                </BootstrapTable>
                            </div>                        
                        </div>
                        <div className="box-footer footer-band">
                        </div>
                    </div>
                </section>
            </div>
        );
    }
}
  
export default TransactionList;