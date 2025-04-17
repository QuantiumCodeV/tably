<template>
  <div class="menu-categories">
    <div class="category-tabs">
      <button 
        v-for="category in categories" 
        :key="category.id"
        :class="['tab-btn', { active: activeCategory === category.id }]"
        @click="setActiveCategory(category.id)"
      >
        {{ category.name }}
      </button>
    </div>
    
    <div class="menu-items-container">
      <template v-if="activeCategory && activeItems.length > 0">
        <table class="menu-table">
          <tbody>
            <tr v-for="row in menuRows" :key="row.id">
              <td v-for="item in row" :key="item.id" class="menu-cell">
                <div 
                  class="menu-item"
                  :class="{ 'unavailable': !item.is_available }"
                >
                  <div class="menu-item-image">
                    <img :src="getImageUrl(item.image)" :alt="item.name">
                    <div v-if="!item.is_available" class="unavailable-overlay">
                      <span>Недоступно</span>
                    </div>
                  </div>
                  <div class="menu-item-info">
                    <h3>{{ item.name }}</h3>
                    <p class="description">{{ item.description }}</p>
                    
                    <!-- Улучшенное отображение ингредиентов -->
                    <div v-if="item.ingredients && item.ingredients.length > 0" class="ingredients-section">
                      <div class="ingredients-header">
                        <span class="ingredients-icon">🍳</span>
                        <h4>Ингредиенты</h4>
                      </div>
                      <div class="ingredients-list">
                        <span 
                          v-for="(ingredient, index) in item.ingredients" 
                          :key="ingredient.id" 
                          class="ingredient-tag"
                        >
                          {{ ingredient.name }}
                          <span v-if="index < item.ingredients.length - 1" class="ingredient-separator">•</span>
                        </span>
                      </div>
                    </div>
                    
                    <div class="menu-item-footer">
                      <p class="price">{{ item.price }} ₽</p>
                      <button 
                        class="btn"
                        :class="{ 'adding': addingToCart === item.id }"
                        :disabled="!item.is_available || loading"
                        @click="addItemToCart(item)"
                      >
                        <span v-if="addingToCart === item.id">Добавлено ✓</span>
                        <span v-else>{{ item.is_available ? 'В корзину' : 'Недоступно' }}</span>
                      </button>
                    </div>
                  </div>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </template>
      
      <div v-else-if="activeCategory && activeItems.length === 0" class="no-items">
        В данной категории нет блюд
      </div>
      
      <div v-else class="no-category">
        Выберите категорию меню
      </div>
    </div>
  </div>
</template>

<script>
import { mapActions, mapState } from 'vuex';

export default {
  name: 'MenuCategories',
  props: {
    categories: {
      type: Array,
      required: true
    }
  },
  data() {
    return {
      activeCategory: null,
      columnsPerRow: 3, // Количество колонок в строке
      addingToCart: null // ID блюда, которое добавляется в корзину (для анимации)
    }
  },
  computed: {
    ...mapState(['loading']),
    
    activeItems() {
      if (!this.activeCategory) return []
      
      const category = this.categories.find(cat => cat.id === this.activeCategory)
      return category ? category.menu_items || [] : []
    },
    // Сортируем блюда так, чтобы недоступные были в конце
    sortedActiveItems() {
      return [...this.activeItems].sort((a, b) => {
        if (a.is_available === b.is_available) return 0
        return a.is_available ? -1 : 1
      })
    },
    // Разбиваем элементы на строки для таблицы
    menuRows() {
      const rows = [];
      const items = this.sortedActiveItems;
      
      for (let i = 0; i < items.length; i += this.columnsPerRow) {
        rows.push(items.slice(i, i + this.columnsPerRow));
      }
      
      return rows;
    }
  },
  methods: {
    ...mapActions(['addToCart']),
    
    setActiveCategory(categoryId) {
      this.activeCategory = categoryId
    },
    getImageUrl(image) {
      // Если есть изображение, используем его, иначе используем заглушку
      return image || '/img/default-dish.jpg'
    },
    async addItemToCart(item) {
      if (item.is_available !== false) {
        console.log('Добавление в корзину:', item.id); // Отладочный вывод
        
        // Используем действие из Vuex для добавления в корзину
        this.addToCart({ 
          menuItemId: item.id, 
          quantity: 1 
        });
        
        // Устанавливаем ID добавляемого блюда для анимации
        this.addingToCart = item.id;
        
        // Сбрасываем анимацию через 1.5 секунды
        setTimeout(() => {
          if (this.addingToCart === item.id) {
            this.addingToCart = null;
          }
        }, 1500);
      }
    },
    // Обновляем количество колонок при изменении размера окна
    updateColumnsCount() {
      if (window.innerWidth <= 768) {
        this.columnsPerRow = 1;
      } else if (window.innerWidth <= 1200) {
        this.columnsPerRow = 2;
      } else {
        this.columnsPerRow = 3;
      }
    }
  },
  mounted() {
    // Устанавливаем первую категорию как активную при монтировании
    if (this.categories.length > 0) {
      this.activeCategory = this.categories[0].id
    }
    
    // Добавляем обработчик изменения размера окна
    window.addEventListener('resize', this.updateColumnsCount);
    
    // Инициализируем количество колонок
    this.updateColumnsCount();
  },
  beforeUnmount() {
    // Удаляем обработчик при размонтировании компонента
    window.removeEventListener('resize', this.updateColumnsCount);
  }
}
</script>

<style scoped>
.menu-categories {
  margin: 2rem 0;
  width: 100%;
}

.category-tabs {
  display: flex;
  overflow-x: auto;
  gap: 0.5rem;
  margin-bottom: 1.5rem;
  padding-bottom: 0.5rem;
}

.tab-btn {
  padding: 0.5rem 1rem;
  background-color: white;
  border: 1px solid #ddd;
  border-radius: 4px;
  cursor: pointer;
  white-space: nowrap;
  transition: all 0.3s;
}

.tab-btn.active {
  background-color: #e74c3c;
  color: white;
  border-color: #e74c3c;
}

.menu-items-container {
  width: 100%;
}

/* Стили для таблицы */
.menu-table {
  width: 100%;
  border-collapse: separate;
  border-spacing: 15px;
  table-layout: fixed; /* Важно для равной ширины ячеек */
}

.menu-cell {
  width: 33.333%; /* Равная ширина для всех ячеек */
  padding: 0;
  vertical-align: top;
}

.menu-item {
  display: flex;
  flex-direction: column;
  background-color: white;
  border-radius: 8px;
  overflow: hidden;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  transition: transform 0.3s;
  height: 100%;
}

.menu-item:hover {
  transform: translateY(-5px);
}

/* Стили для недоступных блюд */
.menu-item.unavailable {
  opacity: 0.8;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
}

.menu-item-image {
  height: 200px;
  position: relative;
  background-color: #f5f5f5;
}

.menu-item-image img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  object-position: center;
}

/* Оверлей для недоступных блюд */
.unavailable-overlay {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5);
  display: flex;
  justify-content: center;
  align-items: center;
  color: white;
  font-weight: bold;
  font-size: 1.2rem;
  text-transform: uppercase;
}

.menu-item-info {
  display: flex;
  flex-direction: column;
  flex-grow: 1;
  padding: 1rem;
}

.menu-item-info h3 {
  margin-bottom: 0.5rem;
  font-size: 1.1rem;
  line-height: 1.3;
  height: 2.8rem;
  overflow: hidden;
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
}

.description {
  color: #666;
  margin-bottom: 1rem;
  font-size: 0.9rem;
  flex-grow: 1;
  overflow: hidden;
  display: -webkit-box;
  -webkit-line-clamp: 3;
  -webkit-box-orient: vertical;
}

/* Улучшенные стили для ингредиентов */
.ingredients-section {
  margin: 1rem 0;
  padding: 0.75rem;
  background-color: #f8f8f8;
  border-radius: 8px;
  border-left: 3px solid #e74c3c;
  box-sizing: border-box;
}

.ingredients-header {
  display: flex;
  align-items: center;
  margin-bottom: 0.5rem;
}

.ingredients-icon {
  font-size: 1.2rem;
  margin-right: 0.5rem;
}

.ingredients-header h4 {
  font-size: 0.95rem;
  color: #333;
  margin: 0;
  font-weight: 600;
}

.ingredients-list {
  display: flex;
  flex-wrap: wrap;
  gap: 0.25rem;
  max-height: 3.5rem;
  overflow-y: auto;
}

.ingredient-tag {
  font-size: 0.85rem;
  color: #555;
  display: inline-flex;
  align-items: center;
}

.ingredient-separator {
  margin: 0 0.25rem;
  color: #e74c3c;
  font-weight: bold;
}

.menu-item-footer {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-top: auto;
  padding-top: 1rem;
}

.price {
  font-weight: bold;
  font-size: 1.2rem;
}

/* Стили для кнопки недоступных блюд */
.menu-item.unavailable .btn {
  opacity: 0.6;
  cursor: not-allowed;
}

.no-items, .no-category {
  text-align: center;
  padding: 2rem;
  background-color: white;
  border-radius: 8px;
  color: #666;
  width: 100%;
}

/* Медиа-запросы для адаптивности */
@media (max-width: 1200px) {
  .menu-cell {
    width: 50%; /* 2 колонки на средних экранах */
  }
}

@media (max-width: 768px) {
  .menu-cell {
    width: 100%; /* 1 колонка на мобильных */
  }
  
  .menu-item-image {
    height: 180px;
  }
}
</style> 