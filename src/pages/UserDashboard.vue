<template>
  <div class="dashboard">
    <h1 class="text-center">Dashboard</h1>

    <!-- Seksioni për shpenzimet dhe të ardhurat -->
    <div class="cards-container">
      <div class="card">
        <h2>Your Expenses</h2>
        <ul>
          <li v-if="expenses.length === 0">No expenses found.</li>
          <li v-for="expense in expenses" :key="expense.id">
            <div class="expense-item">
              <span>{{ expense.category }}</span>
              <p class="description">{{ expense.description }}</p>
              <span class="date">{{ new Date(expense.date).toLocaleDateString() }}</span>
              <span class="amount">- ${{ expense.amount }}</span>
            </div>
          </li>
        </ul>
        <h3>Total Expenses: - ${{ totalExpenses }}</h3> <!-- Display total expenses -->
      </div>

      <div class="card">
        <h2>Your Incomes</h2>
        <ul>
          <li v-if="incomes.length === 0">No incomes found.</li>
          <li v-for="income in incomes" :key="income.id">
            <div class="income-item">
              <span>{{ income.source }}</span>
              <p class="description">{{ income.description }}</p>
              <span class="date">{{ new Date(income.date).toLocaleDateString() }}</span>
              <span class="amount">+ ${{ income.amount }}</span>
            </div>

          </li>
        </ul>
        <h3>Total Incomes: + ${{ totalIncomes }}</h3> <!-- Display total incomes -->
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'UserDashboard',
  data() {
    return {
      user: {},
      expenses: [],
      incomes: [],
      sessionTimeout: 30 * 60 * 1000 // 30 minuta në milisekonda
    };
  },
  computed: {
    totalExpenses() {
      return this.expenses.reduce((sum, expense) => sum + parseFloat(expense.amount), 0).toFixed(2);
    },
    totalIncomes() {
      return this.incomes.reduce((sum, income) => sum + parseFloat(income.amount), 0).toFixed(2);
    }
  },
  created() {
    this.startSessionTimeout();
    this.fetchExpenses();
    this.fetchIncomes();
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
    async fetchExpenses() {
      const userId = localStorage.getItem('userId');
      try {
        const response = await fetch(`http://localhost/backend/src/routes/routes.php?action=get_expenses&id=${userId}`);
        const text = await response.text();
        console.log('Raw response for expenses:', text);
        const data = JSON.parse(text);
        if (response.ok) {
          this.expenses = data.expenses;
        } else {
          console.error('Failed to fetch expenses:', data.message);
        }
      } catch (error) {
        console.error('Error fetching expenses:', error);
      }
    },
    async fetchIncomes() {
      const userId = localStorage.getItem('userId');
      try {
        const response = await fetch(`http://localhost/backend/src/routes/routes.php?action=get_incomes&id=${userId}`);
        const text = await response.text();
        console.log('Raw response for incomes:', text);
        const data = JSON.parse(text);
        if (response.ok) {
          this.incomes = data.incomes;
        } else {
          console.error('Failed to fetch incomes:', data.message);
        }
      } catch (error) {
        console.error('Error fetching incomes:', error);
      }
    }
  }
};
</script>


<style scoped>
.dashboard {
  max-width: 800px;
  margin: 0 auto;
  padding: 20px;
  text-align: center;
}

.text-center {
  font-size: 2em;
  margin-bottom: 20px;
}

.welcome-message {
  font-size: 1.2em;
  color: #555;
  margin-bottom: 30px;
}

.cards-container {
  display: flex;
  justify-content: space-around;
  flex-wrap: wrap;
  gap: 20px;
}

.card {
  background-color: #f9f9f9;
  border-radius: 10px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  padding: 20px;
  width: 100%;
  max-width: 350px;
  text-align: left;
}

.card h2 {
  margin-bottom: 10px;
  font-size: 1.5em;
}

ul {
  list-style: none;
  padding: 0;
}

.expense-item, .income-item {
  display: flex; /* Set to flex for horizontal layout */
  justify-content: space-between; /* Space items evenly */
  align-items: center; /* Center items vertically */
  padding: 8px 0;
  border-bottom: 1px solid #ddd;
}

.amount {
  font-weight: bold;
  color: #d9534f; /* Red for expenses */
}

.income-item .amount {
  color: #5cb85c; /* Green for incomes */
}

.date {
  font-size: 0.8em;
  color: #888;
}

.description {
  font-size: 0.9em;
  color: #666;
  margin-left: 10px; /* Spacing to the left of the description */
}
</style>
