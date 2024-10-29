<template>
    <div class="expense-manager">
      <h2 class="header">Add Expense</h2>
      <form @submit.prevent="addExpense" class="expense-form">
        <input v-model="amount" type="number" placeholder="Amount" required />
        <input v-model="category" type="text" placeholder="Category" required />
        <input v-model="date" type="date" required />
        <textarea v-model="description" placeholder="Description"></textarea>
        <button type="submit" class="btn">Add Expense</button>
      </form>
      <h2 @click="toggleCollapse" class="collapsible-header"> View Expenses <span class="toggle-icon">{{ isCollapsed ? '▼' : '▲' }}</span></h2>
      <div v-if="!isCollapsed" class="collapsible-content">
        <ul class="expense-list">
          <li v-if="expenses.length === 0" class="empty-message">No expenses added yet.</li>
          <li v-for="expense in expenses" :key="expense.id" class="expense-item">
            <div class="expense-details">
              <strong>Category:</strong> {{ expense.category }} <br />
              <strong>Amount:</strong> ${{ expense.amount }} <br />
              <strong>Date:</strong> {{ new Date(expense.date).toLocaleDateString() }} <br />
              <strong>Description:</strong> {{ expense.description }}
            </div>
            <div class="actions">
              <button @click="startEditing(expense)" class="edit-btn">Edit</button>
              <button @click="deleteExpense(expense.id)" class="delete-btn">Delete</button>
            </div>
            <div v-if="isEditing && editExpenseData.id === expense.id" class="edit-form">
              <h3>Edit Expense</h3>
              <input v-model="editExpenseData.amount" type="number" placeholder="Amount" required />
              <input v-model="editExpenseData.category" type="text" placeholder="Category" required />
              <input v-model="editExpenseData.date" type="date" required />
              <textarea v-model="editExpenseData.description" placeholder="Description"></textarea>
              <button @click="updateExpense(expense.id)" class="btn save-btn">Save</button>
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
        category: '',
        date: '',
        description: '',
        expenses: [],
        isEditing: false,
        editExpenseData: {},
        isCollapsed: true,
        sessionTimeout: 30 * 60 * 1000,
      };
    },
    created() {
      this.startSessionTimeout();
      this.fetchExpenses();
    },
    methods: {
      startSessionTimeout() {
      const loginTime = localStorage.getItem('loginTime');
      const currentTime = new Date().getTime();
      
      if (!loginTime) {
        // Vendosim kohën aktuale si login time nëse nuk ekziston
        localStorage.setItem('loginTime', currentTime);
      } else if (currentTime - loginTime > this.sessionTimeout) {
        // Nëse ka kaluar sesioni, dërgojmë përdoruesin tek faqja e loginit
        this.logout();
      } else {
        // Përndryshe, caktojmë një timer që do të ridrejtojë pas periudhës së mbetur
        setTimeout(() => {
          this.logout();
        }, this.sessionTimeout - (currentTime - loginTime));
      }
    },
    logout() {
      alert("Your session has expired. Please log in again.");
      localStorage.removeItem('userId');
      localStorage.removeItem('loginTime');
      this.$router.push('/login');
    },
      toggleCollapse() {
        this.isCollapsed = !this.isCollapsed;
      },
      async addExpense() {
        const userId = localStorage.getItem('userId');
        if (!userId) {
          alert('User ID is not available. Please register or log in.');
          return;
        }
        try {
          const response = await fetch('http://localhost/backend/src/routes/routes.php?action=add_expense', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
            },
            body: JSON.stringify({ user_id: userId, amount: this.amount, category: this.category, date: this.date, description: this.description }),
          });
          if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
          }
          const result = await response.json();
          if (result.error) {
            throw new Error(result.error);
          }
          this.resetForm();
          this.fetchExpenses();
        } catch (error) {
          console.error("Error:", error);
          alert(`An error occurred: ${error.message}`);
        }
      },
      async fetchExpenses() {
        const userId = localStorage.getItem('userId');
        try {
          const response = await fetch(`http://localhost/backend/src/routes/routes.php?action=get_expenses&id=${userId}`);
          const data = await response.json();
          if (response.ok) {
            this.expenses = data.expenses;
          } else {
            console.error('Failed to fetch expenses:', data.message);
          }
        } catch (error) {
          console.error('Error fetching expenses:', error);
        }
      },
      async deleteExpense(expenseId) {
        try {
          const response = await fetch(`http://localhost/backend/src/routes/routes.php?action=delete_expense&id=${expenseId}`, {
            method: 'POST',
          });
          const data = await response.json();
          if (response.ok) {
            this.expenses = this.expenses.filter(expense => expense.id !== expenseId);
          } else {
            console.error('Failed to delete expense:', data.message);
          }
        } catch (error) {
          console.error('Error deleting expense:', error);
        }
      },
      startEditing(expense) {
        this.isEditing = true;
        this.editExpenseData = { ...expense };
      },
      async updateExpense(expenseId) {
        try {
          const response = await fetch(`http://localhost/backend/src/routes/routes.php?action=update_expense&id=${expenseId}`, {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
            },
            body: JSON.stringify(this.editExpenseData),
          });
          const data = await response.json();
          if (response.ok) {
            this.expenses = this.expenses.map(expense => expense.id === expenseId ? { ...this.editExpenseData, id: expenseId } : expense);
            this.cancelEditing();
          } else {
            console.error('Failed to update expense:', data.message || 'Unknown error');
            alert(`Failed to update expense: ${data.message || 'Unknown error'}`);
          }
        } catch (error) {
          console.error('Error updating expense:', error);
          alert(`Error updating expense: ${error.message}`);
        }
      },
      cancelEditing() {
        this.isEditing = false;
        this.editExpenseData = {};
      },
      resetForm() {
        this.amount = '';
        this.category = '';
        this.date = '';
        this.description = '';
      },
    },
  };
  </script>
  
  <style>
  .expense-manager {
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
  .expense-form {
    display: flex;
    flex-direction: column;
    gap: 10px;
  }
  .expense-form input,
  .expense-form textarea {
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 4px;
  }
  .expense-form .btn {
    padding: 10px;
    background-color: #007bff;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    transition: background-color 0.3s ease;
  }
  .expense-form .btn:hover {
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
  .expense-list {
    list-style-type: none;
    padding: 0;
  }
  .expense-item {
    border: 1px solid #ccc;
    padding: 15px;
    margin: 10px 0;
    border-radius: 5px;
    background-color: #fff;
    transition: box-shadow 0.3s ease;
  }
  .expense-item:hover {
    box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
  }
  .expense-details {
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
    color: #888;
  }
  .edit-form {
    margin-top: 10px;
  }
  .save-btn {
    background-color: #007bff;
  }
  .save-btn:hover {
    background-color: #0056b3;
  }
  .cancel-btn {
    background-color: #6c757d;
  }
  .cancel-btn:hover {
    background-color: #5a6268;
  }
  </style>
  