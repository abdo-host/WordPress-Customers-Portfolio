import {StrictMode} from 'react';
import {render} from '@wordpress/element';
import App from "./App";

/**
 * Import the stylesheet for the plugin.
 */
import './style/main.scss';

// Render the App component into the DOM
render(
    <StrictMode>
        <App/>
    </StrictMode>
    , document.getElementById('customers-portfolio-root')
);