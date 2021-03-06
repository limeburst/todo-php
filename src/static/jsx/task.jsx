import React from 'react';

class Task extends React.Component {
  render() {
    if (this.props.task.owner === username) {
      if (this.props.task.is_done) {
        return (
          <li>
            <TaskDoingButton task={this.props.task} />
            <TaskName task={this.props.task} />
          </li>
        );
      } else {
        return (
          <li>
            <TaskDoneButton task={this.props.task} />
            <TaskName task={this.props.task} />
          </li>
        );
      }
    } else {
      return <li><TaskName task={this.props.task} /></li>;
    }
  }
}

class TaskName extends React.Component {
  render() {
    let className = this.props.task.is_done ? "done": "doing";
    return (
      <span className={className}>{this.props.task.name}</span>
    );
  }
}

class TaskDoingButton extends React.Component {
  render() {
    return (
      <form action="/tasks/doing/" method="post">
        <button type="submit">doing</button>
        <input type="hidden" value={this.props.task.id} name="id" />
      </form>
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
      return (
        <ul id="tasks">
          {this.props.tasks.map((task) =>
            <Task key={task.id} task={task} />
          )}
        </ul>
      );
    } else {
      return <p>no done or doing tasks.</p>;
    }
  }
}

export { TaskName, TaskDoneButton, TaskDoingButton, TaskList };
