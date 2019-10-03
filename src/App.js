import React, { Component } from 'react';
import { HashRouter, Route, Switch, Redirect, BrowserRouter , CreateBrowserHistory} from 'react-router-dom';
import { createBrowserHistory } from "history";

import 'react-toastify/dist/ReactToastify.css';
import './App.css';
import Loadable from 'react-loadable';

const loading = () => <div className="animated fadeIn pt-3 text-center">Loading...</div>;

// Containers
const DefaultLayout = Loadable({
  loader: () => import('./containers/DefaultLayout'),
  loading
});

// Pages
const Login = Loadable({
  loader: () => import('./views/Pages/Login'),
  loading
});

// const appHistory = useRouterHistory(createHistory)({
//   basename: "/act_react"
// });

const historyConfig = {
  basename: '/act_react'
};
const history = createBrowserHistory(historyConfig);

// auth
const CanActivate = ({ component: Component, ...rest }) => (
  <Route {...rest} render={(props) => (
    sessionStorage.getItem('isloggedin') === 'true'
      ? <Component {...props} />
      : <Redirect to={{
          pathname: '/login',
          state: { from: props.location }
        }} />
  )} />
)

class App extends Component { 
  
  render() {
    
    return (
      <BrowserRouter history={history} basename={'/act_react'}>
          <Switch>
            <Route exact path="/login" name="Login Page" component={Login} />
            <CanActivate path='/' component={DefaultLayout} />
          </Switch>
      </BrowserRouter>
    );
  }
}

export default App;
