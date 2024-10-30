<template> 
    <div class="homepage">
      <header class="homepage__header">
        <h1>Welcome to Your Personal Finance Manager</h1>
        <p>Manage your finances, track expenses and income, and create monthly budgets effortlessly.</p>
      </header>
  
      <section class="features">
        <div class="feature" @click="$router.push('/add-income')">
          <h2>Add Income</h2>
          <p>Track all sources of income for better insights and financial control.</p>
        </div>
        <div class="feature" @click="$router.push('/add-expense')">
          <h2>Add Expense</h2>
          <p>Record your spending and categorize expenses to stay on top of your budget.</p>
        </div>
        <div class="feature" @click="$router.push('/dashboard')">
          <h2>Dashboard</h2>
          <p>View a summary of your finances, including income and expense trends.</p>
        </div>
        <div class="feature" @click="$router.push('/chart')">
          <h2>Financial Analysis</h2>
          <p>Analyze your expenses with interactive charts for better financial decisions.</p>
        </div>
      </section>
    </div>
  </template>
  
  <script>
  export default {
    name: 'HomePage',
    data() {
      return {
        sessionTimeout: 30 * 60 * 1000, // 30 minutes in milliseconds
      };
    },
    created() {
      this.startSessionTimeout();
    },
    methods: {
      startSessionTimeout() {
        const loginTime = localStorage.getItem('loginTime');
        const currentTime = new Date().getTime();
        
        if (!loginTime) {
          // Set the current time as login time if not already set
          localStorage.setItem('loginTime', currentTime);
        } else if (currentTime - loginTime > this.sessionTimeout) {
          // If session expired, logout
          this.logout();
        } else {
          // Set a timer to logout when session expires
          setTimeout(this.logout, this.sessionTimeout - (currentTime - loginTime));
        }
      },
      logout() {
        alert("Your session has expired. Please log in again.");
        localStorage.removeItem('userId');
        localStorage.removeItem('loginTime');
        this.$router.push('/login');
      }
    }
  };
  </script>
  
  <style scoped>
  .homepage {
    text-align: center;
    padding: 2rem;
  }
  .homepage__header h1 {
    font-size: 2.5rem;
    color: #333;
  }
  .homepage__header p {
    font-size: 1.2rem;
    color: #666;
    margin-bottom: 2rem;
  }
  .features {
    display: flex;
    flex-wrap: wrap;
    gap: 1rem;
    justify-content: center;
  }
  .feature {
    background-color: #f0f0f0;
    padding: 1.5rem;
    width: 250px;
    border-radius: 8px;
    cursor: pointer;
    transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
  }
  .feature:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
  }
  .feature h2 {
    color: #007bff;
    font-size: 1.5rem;
  }
  .feature p {
    color: #333;
  }
  </style>
  