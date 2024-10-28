<template>
    <div class="income-manager">
      <h2 class="header">Add Income</h2>
      <form @submit.prevent="addIncome" class="income-form">
        <input v-model="amount" type="number" placeholder="Amount" required />
        <input v-model="source" type="text" placeholder="Source" required />
        <input v-model="date" type="date" required />
        <textarea v-model="description" placeholder="Description"></textarea>
        <button type="submit" class="btn">Add Income</button>
      </form>
  
      <h2 @click="toggleCollapse" class="collapsible-header">
        View Incomes
        <span class="toggle-icon">{{ isCollapsed ? '▼' : '▲' }}</span>
      </h2>
      <div v-if="!isCollapsed" class="collapsible-content">
        <ul class="income-list">
          <li v-if="incomes.length === 0" class="empty-message">No incomes added yet.</li>
          <li v-for="income in incomes" :key="income.id" class="income-item">
            <div class="income-details">
              <strong>Source:</strong> {{ income.source }} <br />
              <strong>Amount:</strong> ${{ income.amount }} <br />
              <strong>Date:</strong> {{ new Date(income.date).toLocaleDateString() }} <br />
              <strong>Description:</strong> {{ income.description }}
            </div>
            <div class="actions">
              <button @click="startEditing(income)" class="edit-btn">Edit</button>
              <button @click="deleteIncome(income.id)" class="delete-btn">Delete</button>
            </div>
            <div v-if="isEditing && editIncomeData.id === income.id" class="edit-form">
              <h3>Edit Income</h3>
              <input v-model="editIncomeData.amount" type="number" placeholder="Amount" required />
              <input v-model="editIncomeData.source" type="text" placeholder="Source" required />
              <input v-model="editIncomeData.date" type="date" required />
              <textarea v-model="editIncomeData.description" placeholder="Description"></textarea>
              <button @click="updateIncome(income.id)" class="btn save-btn">Save</button>
              <button @click="cancelEditing" class="btn cancel-btn">Cancel</button>
            </div>
          </li>
        </ul>
      </div>
    </div>
  </template>
  
  <script>
  export default {
    data() {
      return {
        amount: '',
        source: '',
        date: '',
        description: '',
        incomes: [],
        isEditing: false,
        editIncomeData: {},
        isCollapsed: true,
      };
    },
    created() {
      this.fetchIncomes();
    },
    methods: {
      toggleCollapse() {
        this.isCollapsed = !this.isCollapsed;
      },
      async addIncome() {
        const userId = localStorage.getItem('userId');
        if (!userId) {
          alert('User ID is not available. Please register or log in.');
          return;
        }
  
        try {
          const response = await fetch('http://localhost/backend/src/routes/routes.php?action=add_income', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
            },
            body: JSON.stringify({
              user_id: userId,
              amount: this.amount,
              source: this.source,
              date: this.date,
              description: this.description,
            }),
          });
  
          if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
          }
  
          const result = await response.json();
          if (result.error) {
            throw new Error(result.error);
          }
  
          this.resetForm();
          this.fetchIncomes();
        } catch (error) {
          console.error("Error:", error);
          alert(`An error occurred: ${error.message}`);
        }
      },
      async fetchIncomes() {
        const userId = localStorage.getItem('userId');
        try {
          const response = await fetch(`http://localhost/backend/src/routes/routes.php?action=get_incomes&id=${userId}`);
          const data = await response.json();
          if (response.ok) {
            this.incomes = data.incomes;
          } else {
            console.error('Failed to fetch incomes:', data.message);
          }
        } catch (error) {
          console.error('Error fetching incomes:', error);
        }
      },
      async deleteIncome(incomeId) {
        try {
          const response = await fetch(`http://localhost/backend/src/routes/routes.php?action=delete_income&id=${incomeId}`, {
            method: 'POST',
          });
          const data = await response.json();
          if (response.ok) {
            this.incomes = this.incomes.filter(income => income.id !== incomeId);
          } else {
            console.error('Failed to delete income:', data.message);
          }
        } catch (error) {
          console.error('Error deleting income:', error);
        }
      },
      startEditing(income) {
        this.isEditing = true;
        this.editIncomeData = { ...income };
      },
  
      async updateIncome(incomeId) {
        try {
          const response = await fetch(`http://localhost/backend/src/routes/routes.php?action=update_income&id=${incomeId}`, {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
            },
            body: JSON.stringify(this.editIncomeData),
          });
      
          const data = await response.json();
      
          if (response.ok) {
            this.incomes = this.incomes.map(income =>
              income.id === incomeId ? { ...this.editIncomeData, id: incomeId } : income
            );
            this.cancelEditing();
          } else {
            console.error('Failed to update income:', data.message || 'Unknown error');
            alert(`Failed to update income: ${data.message || 'Unknown error'}`);
          }
        } catch (error) {
          console.error('Error updating income:', error);
          alert(`Error updating income: ${error.message}`);
        }
      },
      
      cancelEditing() {
        this.isEditing = false;
        this.editIncomeData = {};
      },
      
      resetForm() {
        this.amount = '';
        this.source = '';
        this.date = '';
        this.description = '';
      },
    },
  };
  </script>
  
  <style>
  .income-manager {
    max-width: 600px;
    margin: 20px auto;
    padding: 20px;
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    background-color: #fff;
  }
  
  .header {
    margin-bottom: 20px;
    font-size: 24px;
    font-weight: bold;
    text-align: center;
  }
  
  .income-form {
    display: flex;
    flex-direction: column;
    gap: 10px;
  }
  
  .income-form input,
  .income-form textarea {
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 4px;
  }
  
  .income-form .btn {
    padding: 10px;
    background-color: #007bff;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    transition: background-color 0.3s ease;
  }
  
  .income-form .btn:hover {
    background-color: #0056b3;
  }
  
  .collapsible-header {
    cursor: pointer;
    background-color: #f1f1f1;
    padding: 10px;
    border-radius: 5px;
    margin: 10px 0;
    display: flex;
    justify-content: space-between;
    align-items: center;
    transition: background-color 0.3s ease;
  }
  
  .collapsible-header:hover {
    background-color: #e0e0e0;
  }
  
  .toggle-icon {
    font-size: 18px;
  }
  
  .collapsible-content {
    margin-top: 10px;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
    background-color: #f9f9f9;
  }
  
  .income-list {
    list-style-type: none;
    padding: 0;
  }
  
  .income-item {
    border: 1px solid #ccc;
    padding: 15px;
    margin: 10px 0;
    border-radius: 5px;
    background-color: #fff;
    transition: box-shadow 0.3s ease;
  }
  
  .income-item:hover {
    box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
  }
  
  .income-details {
    margin-bottom: 10px;
  }
  
  .actions {
    display: flex;
    gap: 10px;
  }
  
  .edit-btn,
  .delete-btn {
    padding: 5px 10px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    transition: background-color 0.3s ease;
  }
  
  .edit-btn {
    background-color: #28a745;
    color: white;
  }
  
  .edit-btn:hover {
    background-color: #218838;
  }
  
  .delete-btn {
    background-color: #dc3545;
    color: white;
  }
  
  .delete-btn:hover {
    background-color: #c82333;
  }
  
  .empty-message {
    text-align: center;
    color: #888;
  }
  </style>
  