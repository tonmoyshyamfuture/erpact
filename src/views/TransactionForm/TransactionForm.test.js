import React from 'react';
import ReactDOM from 'react-dom';
import TransactionForm from './TransactionForm';

it('renders without crashing', () => {
    const div = document.createElement('div');
    ReactDOM.render(<TransactionForm />, div);
    ReactDOM.unmountComponentAtNode(div);
});
