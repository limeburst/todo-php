import React from 'react';

class Task extends React.Component {
    render() {
        return (
            <li>
                <TaskDoneButton task={this.props.task} />
                <TaskName task={this.props.task} />
            </li>
        );
    }
}

class TaskName extends React.Component {
    render() {
        let className = this.props.task.done ? "done": "doing";
        return (
            <span className={className}>{this.props.task.name}</span>
        );
    }
}

class TaskDoneButton extends React.Component {
    render() {
        return (
            <form action="/tasks/done/" method="post">
                <button type="submit">done!</button>
                <input type="hidden" value={this.props.task.id} name="id" />
            </form>
        );
    }
}

class TaskList extends React.Component {
    render() {
        if (this.props.tasks.length) {
            const tasks = this.props.tasks.map(
                (task) => (
                    <Task key={task.id} task={task} />
                )
            );
            return (<ul id="tasks">{tasks}</ul>);
        } else {
            return <p>no done or doing tasks.</p>;
        }
    }
}

export { TaskName, TaskDoneButton, TaskList };
