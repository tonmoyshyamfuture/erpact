import React from 'react';
import ReactDOM from 'react-dom';
import TransactionList from './Dashboard';

it('renders without crashing', () => {
    const div = document.createElement('div');
    ReactDOM.render(<TransactionList />, div);
    ReactDOM.unmountComponentAtNode(div);
});
