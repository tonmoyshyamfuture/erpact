import React from 'react';
import ReactDOM from 'react-dom';
import SettingSideBar from './SettingSideBar';

it('renders without crashing', () => {
    const div = document.createElement('div');
    ReactDOM.render(<SettingSideBar />, div);
    ReactDOM.unmountComponentAtNode(div);
});
