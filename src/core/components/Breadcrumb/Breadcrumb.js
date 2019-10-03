import React, { Component, Suspense } from 'react';
import { Link } from "react-router-dom";

const SettingSideBar = React.lazy(() => import('../SettingSideBar'));

class Breadcrumb extends Component {
    constructor(props) {
        super(props);
        
        this.state = {
            sideBarKey: false
        }

        this.openSettingSideBar = this.openSettingSideBar.bind(this)
    }
    
    loading = () => <div className="animated fadeIn pt-1 text-center">Loading...</div>

    openSettingSideBar = () => {
        this.setState({
            sideBarKey: true
        })
    }

    getFormSettingData = (data) =>{
        this.props.getFormSettingData(data);
    }

    getSideBarKey = (key) => {
        this.setState({
            sideBarKey: key
        })
    }

    render() {
        return (
            <React.Fragment>                
                <section className="content-header">
                    <div className="row">
                        <div className="col-xs-5">
                            <h1><i className="fa fa-list"></i> {this.props.action} {this.props.title}</h1>
                        </div>
                        <div className="col-xs-7 text-right">
                            <div className="btn-wrapper">
                                <div className="btn-group btn-group-sm">
                                    <a href="http://localhost/acthtml/sales-list.php" className="btn btn-default">Cancel</a>
                                    <a href="" className="btn btn-success">Save</a>
                                </div>
                                <a onClick={this.openSettingSideBar} className="btn btn-sm btn-default openRightBar"><i className="fa fa-cog"></i></a>
                            </div>
                        </div>
                    </div>
                </section>
                <section className="clearfix">
                    <div className="col-md-12">
                        <ol className="breadcrumb">
                            <li><a href=""><i className="fa fa-dashboard"></i> Transactions</a></li>
                            <li><a href="">{this.props.title}</a></li>
                            <li className="active">{this.props.action} </li>
                        </ol>
                    </div>
                </section>                
                <div className={this.state.sideBarKey ? 'right-sidebar opened' : 'right-sidebar'}>
                    <Suspense  fallback={this.loading()}>
                        <SettingSideBar getSideBarKey={this.getSideBarKey} getFormSettingData={this.getFormSettingData} preferencesDetails={this.props.preferencesDetails}/>
                    </Suspense>
                </div>
            </React.Fragment>
        );
    }
}
  
export default Breadcrumb;