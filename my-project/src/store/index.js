import { createStore } from 'vuex'
import axios from 'axios'

const API_URL = 'http://127.0.0.1:8000/api'

// Получаем корзину и сессию из localStorage при инициализации
const savedCart = localStorage.getItem('cart')
const initialCart = savedCart ? JSON.parse(savedCart) : { items: [], total: 0 }
const sessionId = localStorage.getItem('sessionId')

export default createStore({
  state: {
    sessionId: sessionId || null,
    tableId: localStorage.getItem('tableId') || null,
    restaurant: null,
    menuCategories: [],
    cart: initialCart,
    loading: false,
    error: null
  },
  getters: {
    cartItemCount(state) {
      return state.cart.items.reduce((total, item) => total + (item.quantity || 0), 0)
    },
    cartTotal(state) {
      return state.cart.items.reduce((total, item) => total + ((item.price || 0) * (item.quantity || 0)), 0)
    },
    isSessionActive(state) {
      return !!state.sessionId
    }
  },
  mutations: {
    setSessionId(state, sessionId) {
      state.sessionId = sessionId
      if (sessionId) {
        localStorage.setItem('sessionId', sessionId)
      } else {
        localStorage.removeItem('sessionId')
      }
    },
    setTableId(state, tableId) {
      state.tableId = tableId
      if (tableId) {
        localStorage.setItem('tableId', tableId)
      } else {
        localStorage.removeItem('tableId')
      }
    },
    setTable(state, table) {
      state.table = table
    },
    setRestaurant(state, restaurant) {
      state.restaurant = restaurant
    },
    setMenuCategories(state, categories) {
      state.menuCategories = categories
    },
    addToCart(state, { menuItem, quantity = 1 }) {
      const existingItem = state.cart.items.find(item => item.id === menuItem.id)
      
      if (existingItem) {
        existingItem.quantity += quantity
      } else {
        state.cart.items.push({
          id: menuItem.id,
          name: menuItem.name,
          price: menuItem.price,
          image: menuItem.image,
          quantity: quantity
        })
      }
      
      // Пересчитываем общую сумму
      state.cart.total = state.cart.items.reduce(
        (total, item) => total + (item.price * item.quantity), 
        0
      )
      
      // Сохраняем корзину в localStorage
      localStorage.setItem('cart', JSON.stringify(state.cart))
    },
    updateCartItem(state, { itemId, quantity }) {
      const item = state.cart.items.find(item => item.id === itemId)
      
      if (item) {
        item.quantity = quantity
        
        // Пересчитываем общую сумму
        state.cart.total = state.cart.items.reduce(
          (total, item) => total + (item.price * item.quantity), 
          0
        )
        
        // Сохраняем корзину в localStorage
        localStorage.setItem('cart', JSON.stringify(state.cart))
      }
    },
    removeFromCart(state, itemId) {
      state.cart.items = state.cart.items.filter(item => item.id !== itemId)
      
      // Пересчитываем общую сумму
      state.cart.total = state.cart.items.reduce(
        (total, item) => total + (item.price * item.quantity), 
        0
      )
      
      // Сохраняем корзину в localStorage
      localStorage.setItem('cart', JSON.stringify(state.cart))
    },
    clearCart(state) {
      state.cart = { items: [], total: 0 }
      
      // Очищаем корзину в localStorage
      localStorage.removeItem('cart')
    },
    setLoading(state, loading) {
      state.loading = loading
    },
    setError(state, error) {
      state.error = error
    }
  },
  actions: {
    // Создание новой сессии
    async createSession({ commit }, tableId) {
      try {
        commit('setLoading', true)
        
        const response = await axios.post(`${API_URL}/session/create`, {
          table_id: tableId
        })
        
        if (response.data && response.data.session_id) {
          commit('setSessionId', response.data.session_id)
          commit('setTableId', tableId)
          commit('setError', null)
          return { success: true, sessionId: response.data.session_id }
        } else {
          commit('setError', 'Не удалось создать сессию')
          return { success: false, error: 'Не удалось создать сессию' }
        }
      } catch (error) {
        console.error('Ошибка при создании сессии:', error)
        
        let errorMessage = 'Ошибка при создании сессии'
        
        // Проверяем, есть ли сообщение об ошибке от сервера
        if (error.response && error.response.data && error.response.data.error) {
          errorMessage = error.response.data.error
        }
        
        commit('setError', errorMessage)
        return { success: false, error: errorMessage }
      } finally {
        commit('setLoading', false)
      }
    },
    
    // Загрузка данных стола и меню
    async loadTableData({ commit, state, dispatch }, tableId) {
      try {
        commit('setLoading', true)
        
        // Сохраняем ID стола
        commit('setTableId', tableId)
        
        // Если нет активной сессии, пытаемся создать новую
        if (!state.sessionId) {
          const sessionResult = await dispatch('createSession', tableId)
          if (!sessionResult.success) {
            return { success: false, error: sessionResult.error }
          }
        }
        
        // Загружаем данные стола и меню
        const response = await axios.get(`${API_URL}/tables/${tableId}`)
        
        commit('setRestaurant', response.data.restaurant)
        commit('setMenuCategories', response.data.menu_categories)
        commit('setTable', response.data.table)
        commit('setError', null)
        
        return { success: true }
      } catch (error) {
        console.error('Ошибка при загрузке данных стола:', error)
        
        let errorMessage = 'Ошибка при загрузке данных стола'
        
        // Проверяем, есть ли сообщение об ошибке от сервера
        if (error.response && error.response.data && error.response.data.error) {
          errorMessage = error.response.data.error
        }
        
        commit('setError', errorMessage)
        return { success: false, error: errorMessage }
      } finally {
        commit('setLoading', false)
      }
    },
    
    // Добавление в корзину
    addToCart({ commit, state }, { menuItemId, quantity = 1 }) {
      // Находим блюдо по ID
      let menuItem = null
      
      for (const category of state.menuCategories) {
        const foundItem = category.menu_items.find(item => item.id === menuItemId)
        if (foundItem) {
          menuItem = foundItem
          break
        }
      }
      
      if (menuItem) {
        commit('addToCart', { menuItem, quantity })
      }
    },
    
    // Обновление количества в корзине
    updateCartItem({ commit }, { itemId, quantity }) {
      commit('updateCartItem', { itemId, quantity })
    },
    
    // Удаление из корзины
    removeFromCart({ commit }, itemId) {
      commit('removeFromCart', itemId)
    },
    
    // Очистка корзины
    clearCart({ commit }) {
      commit('clearCart')
    },
    
    // Оформление заказа
    async checkout({ state, commit }, { paymentMethod = 'card' } = {}) {
      try {
        commit('setLoading', true)
        
        if (!state.sessionId) {
          commit('setError', 'Нет активной сессии')
          return { success: false, error: 'Нет активной сессии' }
        }
        
        // Отправляем заказ на сервер
        const orderData = {
          session_id: state.sessionId,
          payment_method: paymentMethod,
          items: state.cart.items.map(item => ({
            id: item.id,
            quantity: item.quantity,
            price: item.price
          }))
        }
        
        const response = await axios.post(`${API_URL}/orders/create`, orderData)
        
        if (response.data && response.data.order_id) {
          // Очищаем корзину после успешного оформления заказа
          commit('clearCart')
          commit('setError', null)
          return { 
            success: true, 
            orderId: response.data.order_id 
          }
        } else {
          commit('setError', 'Не удалось оформить заказ')
          return { success: false, error: 'Не удалось оформить заказ' }
        }
      } catch (error) {
        console.error('Ошибка при оформлении заказа:', error)
        
        let errorMessage = 'Ошибка при оформлении заказа'
        
        // Проверяем, есть ли сообщение об ошибке от сервера
        if (error.response && error.response.data && error.response.data.error) {
          errorMessage = error.response.data.error
        }
        
        commit('setError', errorMessage)
        return { success: false, error: errorMessage }
      } finally {
        commit('setLoading', false)
      }
    }
  }
}) 