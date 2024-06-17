// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.css';

import {createRoot} from 'react-dom/client';
import React, { StrictMode } from 'react';
import App from './react/components/App';

const container = document.getElementById('app');
const root = createRoot(container);

root.render(
    <StrictMode>
        <App />
    </StrictMode>
)
