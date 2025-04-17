<template>
  <div class="not-found">
    <div class="container">
      <h1>404</h1>
      <h2>Страница не найдена</h2>
      <p>Извините, запрашиваемая страница не существует.</p>
      <router-link to="/" class="btn">Вернуться на главную</router-link>
    </div>
  </div>
</template>

<script>
export default {
  name: 'NotFound'
}
</script>

<style scoped>
.not-found {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  min-height: 100vh;
  text-align: center;
  padding: 2rem;
}

.not-found h1 {
  font-size: 6rem;
  color: #e74c3c;
  margin-bottom: 1rem;
}

.not-found h2 {
  font-size: 2rem;
  margin-bottom: 1rem;
}

.not-found p {
  color: #666;
  margin-bottom: 2rem;
}
</style> 