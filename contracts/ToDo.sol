// SPDX-License-Identifier: MIT
pragma solidity ^0.8.0;

contract ToDo {
    struct Task {
        uint id;
        string content;
        bool completed;
        uint timestamp;
    }

    mapping(address => Task[]) private userTasks;
    uint private taskId;

    function addTask(string memory _content) public {
        Task memory newTask = Task(taskId, _content, false, block.timestamp);
        userTasks[msg.sender].push(newTask);
        taskId++;
    }

    function getTasks() public view returns (Task[] memory) {
        return userTasks[msg.sender];
    }

    function toggleTaskCompletion(uint _taskId) public {
        Task[] storage tasks = userTasks[msg.sender];
        for (uint i = 0; i < tasks.length; i++) {
            if (tasks[i].id == _taskId) {
                tasks[i].completed = !tasks[i].completed;
                break;
            }
        }
    }
}
