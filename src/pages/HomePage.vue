<template>
    <div class="homepage">
      <header>
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
  };
  </script>
  
  <style scoped>
  * {
    box-sizing: border-box;
  }
  
  body {
    font-family: Arial, sans-serif;
    color: #333;
  }
  
  .homepage {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 40px;
    background-color: #f5f7fa;
  }
  
  header {
    text-align: center;
    max-width: 700px;
    margin-bottom: 40px;
  }
  
  header h1 {
    font-size: 36px;
    margin-bottom: 10px;
    color: #3a7afe;
  }
  
  header p {
    font-size: 18px;
    color: #666;
  }
  
  .features {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    justify-content: center;
  }
  
  .feature {
    width: 200px;
    padding: 20px;
    background-color: #fff;
    border-radius: 10px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    cursor: pointer;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    text-align: center;
  }
  
  .feature:hover {
    transform: translateY(-5px);
    box-shadow: 0 6px 15px rgba(58, 122, 254, 0.3);
  }
  
  .feature h2 {
    font-size: 22px;
    color: #3a7afe;
    margin-bottom: 10px;
  }
  
  .feature p {
    color: #666;
    font-size: 15px;
  }
  </style>
  