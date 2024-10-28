<template>
  <div>
    <h1>Register</h1>
    <form @submit.prevent="registerUser">
      <div>
        <label for="name">Name:</label>
        <input type="text" id="name" v-model="name" required />
      </div>
      <div>
        <label for="email">Email:</label>
        <input type="email" id="email" v-model="email" required />
      </div>
      <div>
        <label for="password">Password:</label>
        <input type="password" id="password" v-model="password" required />
      </div>
      <button type="submit">Register</button>
    </form>
    <p v-if="message">{{ message }}</p>
  </div>
</template>

<script>
export default {
  data() {
    return {
      name: '',
      email: '',
      password: '',
      message: '',
    };
  },
  methods: {
    async registerUser() {
      try {
        const response = await fetch('http://localhost/backend/src/routes/routes.php?action=register', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
          },
          body: JSON.stringify({
            name: this.name,
            email: this.email,
            password: this.password,
          }),
        });

        const result = await response.json();
        this.message = result.message;

        if (result.status === 'success') {
          // Ruaj ID-në e përdoruesit në localStorage
          localStorage.setItem('userId', result.userId);

          // Pas regjistrimit, mund të ridrejtohesh ose të bllokosh formën
          this.name = '';
          this.email = '';
          this.password = '';
        }
      } catch (error) {
        console.error('Error:', error);
      }
    },
  },
};
</script>

<style>
/* Stilizimi i formës (opsional) */
</style>