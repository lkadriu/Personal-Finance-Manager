<template>
    <div class="chart-container">
      <h2>Income and Expense Chart</h2>
      <canvas id="incomeExpenseChart"></canvas>
    </div>
  </template>
  
  <script>
  import { Chart } from 'chart.js/auto';
  import zoomPlugin from 'chartjs-plugin-zoom';
  
  Chart.register(zoomPlugin);
  
  export default {
    name: 'IncomeExpenseChart',
    data() {
      return {
        chart: null,
        incomes: [],
        expenses: [],
        sessionTimeout: 30 * 60 * 1000 // 30 minuta në milisekonda
      };
    },
    async created() {
      await this.fetchData();
      this.startSessionTimeout();
      this.renderChart();
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
      async fetchData() {
        const userId = localStorage.getItem('userId');
  
        try {
          const expensesResponse = await fetch(`http://localhost/backend/src/routes/routes.php?action=get_expenses&id=${userId}`);
          const expensesData = await expensesResponse.json();
          this.expenses = expensesData.expenses;
  
          const incomesResponse = await fetch(`http://localhost/backend/src/routes/routes.php?action=get_incomes&id=${userId}`);
          const incomesData = await incomesResponse.json();
          this.incomes = incomesData.incomes;
        } catch (error) {
          console.error("Error fetching data:", error);
        }
      },
      renderChart() {
        const incomeData = this.groupDataByDate(this.incomes);
        const expenseData = this.groupDataByDate(this.expenses);
  
        const dates = [...new Set([...Object.keys(incomeData), ...Object.keys(expenseData)])].sort();
        const incomeValues = dates.map((date) => incomeData[date] || 0);
        const expenseValues = dates.map((date) => expenseData[date] || 0);
  
        const ctx = document.getElementById("incomeExpenseChart").getContext("2d");
  
        if (this.chart) {
          this.chart.destroy();
        }
  
        this.chart = new Chart(ctx, {
          type: "line",
          data: {
            labels: dates,
            datasets: [
              {
                label: "Incomes",
                data: incomeValues,
                borderColor: "#5cb85c",
                backgroundColor: "rgba(92, 184, 92, 0.1)",
                fill: true,
              },
              {
                label: "Expenses",
                data: expenseValues,
                borderColor: "#d9534f",
                backgroundColor: "rgba(217, 83, 79, 0.1)",
                fill: true,
              },
            ],
          },
          options: {
            responsive: true,
            plugins: {
              legend: { position: "top" },
              zoom: {
                pan: {
                  enabled: true,
                  mode: 'xy',
                },
                zoom: {
                  wheel: { enabled: true },
                  pinch: { enabled: true },
                  mode: 'xy',
                },
              },
            },
            scales: {
              x: { title: { display: true, text: "Date" } },
              y: { title: { display: true, text: "Amount ($)" } },
            },
          },
        });
      },
      groupDataByDate(data) {
        return data.reduce((acc, item) => {
          const date = new Date(item.date).toLocaleDateString();
          acc[date] = (acc[date] || 0) + item.amount;
          return acc;
        }, {});
      },
    },
  };
  </script>
  
  <style scoped>
  .chart-container {
    max-width: 800px;
    margin: 0 auto;
    padding: 20px;
    text-align: center;
  }
  </style>
  