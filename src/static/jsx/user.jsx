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
        if (this.props.tasks.length) {
            const tasks = this.props.tasks.map(
                (task) => <Task
                    id={task.id}
                    key={task.id}
                    name={task.name}
                    done={task.done}
                />
            );
            return (<ul>{tasks}</ul>);
        } else {
            return <p>no done or doing tasks.</p>;
        }
    }
}

$.get('tasks', function(res) {
    ReactDOM.render(
        <TaskList name="test" tasks={res} />,
        document.getElementById('root')
    );
});
