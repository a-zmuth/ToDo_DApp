let account;
let contract; 
let ganacheUrl;

// Fetch configuration from PHP
async function fetchConfig() {
    try {
        const response = await fetch('config.php');
        const config = await response.json();
        ganacheUrl = config.ganacheUrl;
        const abi = config.abi;
        const contractAddress = config.contractAddress;

        // Initialise Web3 and contract
        const web3 = new window.Web3(ganacheUrl);
        contract = new web3.eth.Contract(abi, contractAddress);
    } catch (error) {
        console.error('Error fetching config:', error);
        alert('Failed to load contract configuration.');
    }
}

// Call fetchConfig on page load
fetchConfig();

// Connect Wallet
document.getElementById('connectWallet').addEventListener('click', async () => {
    if (window.ethereum) {
        try {
            const accounts = await ethereum.request({ method: 'eth_requestAccounts' });
            account = accounts[0];

            // Send wallet address and username to PHP
            const response = await fetch('wallet.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ wallet: account, username: "User" }) 
            });
            const result = await response.json();

            if (result.status === "success") {
                document.getElementById('profile').innerText = `Connected Wallet: ${account}`;
                fetchTasks(); // Fetch tasks after successful connection
            } else {
                alert(result.message);
            }
        } catch (error) {
            console.error('Error connecting wallet:', error);
            alert('Failed to connect wallet.');
        }
    } else {
        alert('MetaMask is not installed');
    }
});

// Save Task
document.getElementById('saveReport').addEventListener('click', async () => {
    const report = document.getElementById('report').value;
    if (!account) {
        alert('Please connect your wallet first.');
        return;
    }

    try {
        const response = await fetch('saveReport.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ wallet: account, task: report })
        });
        const result = await response.json();

        if (result.status === "success") {
            alert('Task saved!');
            await addTask(report); // Add the task to the blockchain
            fetchTasks(); // Refresh tasks after saving
        } else {
            alert(result.message);
        }
    } catch (error) {
        console.error('Error saving task:', error);
        alert('Failed to save task.');
    }
});

// Fetch Tasks
async function fetchTasks() {
    if (!account) {
        alert('Please connect your wallet first.');
        return;
    }

    try {
        const response = await fetch(`fetch.php?wallet=${account}`);
        const tasks = await response.json();

        const taskList = document.getElementById('taskList');
        taskList.innerHTML = tasks.map(task => `<li>${task.task} - ${task.created_at}</li>`).join('');
    } catch (error) {
        console.error('Error fetching tasks:', error);
        alert('Failed to fetch tasks.');
    }
}

// Interact with the Blockchain 
async function addTask(content) {
    try {
        await contract.methods.addTask(content).send({ from: account });
        alert("Task added to blockchain!");
    } catch (error) {
        console.error('Failed to add task to blockchain:', error);
        alert("Failed to add task to blockchain.");
    }
}

// Fetch Tasks on Page Load
if (account) {
    fetchTasks();
}