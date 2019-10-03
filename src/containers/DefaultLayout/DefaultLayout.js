import React, { Component, Suspense } from 'react';
import { Redirect, Route, Switch } from 'react-router-dom';

// routes config
import routes from '../../routes';

const DefaultHeader = React.lazy(() => import('./DefaultHeader'));
const DefaultFooter = React.lazy(() => import('./DefaultFooter'));
const DefaultLeftMenu = React.lazy(() => import('./DefaultLeftMenu'));

class DefaultLayout extends Component {

  loading = () => <div className="animated fadeIn pt-1 text-center">Loading...</div>
  
  constructor(props) {
    super(props);

    this.state = {
      toggleKey: false
    };
  }

  settingLeftMenu = (key) => {
    this.setState({
      toggleKey: key
    })
  }
  render() {
    return (
      <div className={ this.state.toggleKey ? 'sidebar-collapse skin-light fixed' : 'skin-light fixed' }>
        <div className="wrapper">
          <Suspense  fallback={this.loading()}>
            <DefaultHeader settingLeftMenu={this.settingLeftMenu}/>
          </Suspense>
          <Suspense  fallback={this.loading()}>
            <DefaultLeftMenu />
          </Suspense>
          <Suspense fallback={this.loading()}>
              <Switch>
                  {routes.map((route, idx) => {
                  return route.component ? (
                      <Route
                      key={idx}
                      path={route.path}
                      render={props => (
                        <route.component {...props} />
                      )} />
                  ) : (null);
                  })}
                  <Redirect from="/" to="/dashboard" />
              </Switch>
          </Suspense>
          {/* <Suspense fallback={this.loading()}>
            <DefaultFooter />
          </Suspense> */}
        </div>        
      </div>
    );
  }
}

export default DefaultLayout;
