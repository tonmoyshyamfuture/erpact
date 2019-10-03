import React from 'react';

const Dashboard = React.lazy(() => import('./views/Dashboard'));
const TransactionForm = React.lazy(() => import('./views/TransactionForm'));
const TransactionList = React.lazy(() => import('./views/TransactionList'));

const routes = [
    { path: '/dashboard', component: Dashboard },
    { path: '/transaction/:name/:tran_type/:id', component: TransactionForm },
    { path: '/transaction/:name/:tran_type', component: TransactionForm },
    { path: '/:name/:tran_type', component: TransactionList },
];
  
export default routes;