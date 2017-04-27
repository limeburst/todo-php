import $ from 'jquery';
import React from 'react';
import ReactDOM from 'react-dom';

import { TaskList } from './task.jsx';

$.get('tasks', function(res) {
  ReactDOM.render(
    <TaskList tasks={res} />,
    document.getElementById('root')
  );
});
