import React from 'react';
import LiveRaceTable from './LiveRaceTable/LiveRaceTable';
//import Cars from './../../../../nodejs/javascript/examples/dev-stack/src/client/cars.js';

const app = document.getElementById('actual-race-live');
React.render(<LiveRaceTable />, app);
