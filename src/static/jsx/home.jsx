import $ from 'jquery';
import React from 'react';
import ReactDOM from 'react-dom';

import { TaskList } from './task.jsx';

$.get(`/users/${username}/tasks`, function(res) {
  ReactDOM.render(
    <TaskList tasks={res.filter((task) => { return !task.is_done }).reverse()} user={username} />,
    document.getElementById('root')
  );
});
