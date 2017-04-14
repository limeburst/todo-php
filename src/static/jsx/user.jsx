import $ from 'jquery';
import React from 'react';
import ReactDOM from 'react-dom';

class Task extends React.Component {
    render() {
        let className = this.props.done ? "done": "doing";
        return (
            <li className={className}>{this.props.name}</li>
        );
    }
}

class TaskList extends React.Component {
    render() {
        const tasks = this.props.tasks.map(
            (task) => <Task
                id={task.id}
                key={task.id}
                name={task.name}
                done={task.done}
            />
        );
        return (<ul>{tasks}</ul>);
    }
}

$.get('tasks', function(res) {
    ReactDOM.render(
        <TaskList name="test" tasks={res} />,
        document.getElementById('root')
    );
});
