(function(){"use strict";var e={1504:function(e,t,a){var s=a(5130),r=a(6768);const n={id:"app"};function i(e,t,a,s,i,o){const c=(0,r.g2)("router-view");return(0,r.uX)(),(0,r.CE)("div",n,[(0,r.bF)(c)])}var o={name:"App"},c=a(1241);const l=(0,c.A)(o,[["render",i]]);var d=l,u=a(1387),m=a(4232);const p={class:"table-view"},v={key:0,class:"loading"},y={key:1,class:"error"},h={class:"container"},C={class:"table-info"};function b(e,t,a,s,n,i){const o=(0,r.g2)("RestaurantHeader"),c=(0,r.g2)("MenuCategories"),l=(0,r.g2)("CartSidebar");return(0,r.uX)(),(0,r.CE)("div",p,[i.loading?((0,r.uX)(),(0,r.CE)("div",v,t[1]||(t[1]=[(0,r.Lk)("div",{class:"spinner"},null,-1),(0,r.Lk)("p",null,"Загрузка...",-1)]))):i.error?((0,r.uX)(),(0,r.CE)("div",y,[(0,r.Lk)("p",null,(0,m.v_)(i.error),1),(0,r.Lk)("button",{onClick:t[0]||(t[0]=(...e)=>i.retryLoading&&i.retryLoading(...e)),class:"btn"},"Попробовать снова")])):((0,r.uX)(),(0,r.CE)(r.FK,{key:2},[(0,r.bF)(o,{restaurant:i.restaurant},null,8,["restaurant"]),(0,r.Lk)("div",h,[(0,r.Lk)("div",C,[(0,r.Lk)("h2",null,"Стол №"+(0,m.v_)(i.table.number),1),(0,r.Lk)("p",null,"Вместимость: "+(0,m.v_)(i.table.capacity)+" человек",1)]),(0,r.bF)(c,{categories:i.menuCategories,onAddToCart:i.addToCart},null,8,["categories","onAddToCart"]),(0,r.bF)(l,{cart:i.cart,onUpdateItem:i.updateCartItem,onRemoveItem:i.removeCartItem,onClearCart:i.clearCart},null,8,["cart","onUpdateItem","onRemoveItem","onClearCart"])])],64))])}const k={class:"restaurant-header"},g={class:"container"},f={class:"header-content"},L={class:"logo"},I=["src","alt"],M={class:"restaurant-info"},_={key:0},E={class:"contact-info"},S={key:0},w={key:1};function P(e,t,a,s,n,i){return(0,r.uX)(),(0,r.CE)("header",k,[(0,r.Lk)("div",g,[(0,r.Lk)("div",f,[(0,r.Lk)("div",L,[(0,r.Lk)("img",{src:i.getLogoUrl(a.restaurant.logo),alt:a.restaurant.name},null,8,I)]),(0,r.Lk)("div",M,[(0,r.Lk)("h1",null,(0,m.v_)(a.restaurant.name),1),a.restaurant.description?((0,r.uX)(),(0,r.CE)("p",_,(0,m.v_)(a.restaurant.description),1)):(0,r.Q3)("",!0),(0,r.Lk)("div",E,[a.restaurant.address?((0,r.uX)(),(0,r.CE)("p",S,[t[0]||(t[0]=(0,r.Lk)("strong",null,"Адрес:",-1)),(0,r.eW)(" "+(0,m.v_)(a.restaurant.address),1)])):(0,r.Q3)("",!0),a.restaurant.phone?((0,r.uX)(),(0,r.CE)("p",w,[t[1]||(t[1]=(0,r.Lk)("strong",null,"Телефон:",-1)),(0,r.eW)(" "+(0,m.v_)(a.restaurant.phone),1)])):(0,r.Q3)("",!0)])])])])])}var T={name:"RestaurantHeader",props:{restaurant:{type:Object,required:!0}},methods:{getLogoUrl(e){return e||"/img/default-logo.png"}}};const X=(0,c.A)(T,[["render",P],["__scopeId","data-v-2a44a7ba"]]);var O=X;const q={class:"menu-categories"},A={class:"category-tabs"},F=["onClick"],$={class:"menu-items-container"},Q={key:0,class:"menu-table"},R={class:"menu-item-image"},j=["src","alt"],N={key:0,class:"unavailable-overlay"},V={class:"menu-item-info"},U={class:"description"},W={key:0,class:"ingredients-section"},z={class:"ingredients-list"},K={key:0,class:"ingredient-separator"},D={class:"menu-item-footer"},x={class:"price"},H=["disabled","onClick"],J={key:0},B={key:1},G={key:1,class:"no-items"},Y={key:2,class:"no-category"};function Z(e,t,a,s,n,i){return(0,r.uX)(),(0,r.CE)("div",q,[(0,r.Lk)("div",A,[((0,r.uX)(!0),(0,r.CE)(r.FK,null,(0,r.pI)(a.categories,(e=>((0,r.uX)(),(0,r.CE)("button",{key:e.id,class:(0,m.C4)(["tab-btn",{active:n.activeCategory===e.id}]),onClick:t=>i.setActiveCategory(e.id)},(0,m.v_)(e.name),11,F)))),128))]),(0,r.Lk)("div",$,[n.activeCategory&&i.activeItems.length>0?((0,r.uX)(),(0,r.CE)("table",Q,[(0,r.Lk)("tbody",null,[((0,r.uX)(!0),(0,r.CE)(r.FK,null,(0,r.pI)(i.menuRows,(a=>((0,r.uX)(),(0,r.CE)("tr",{key:a.id},[((0,r.uX)(!0),(0,r.CE)(r.FK,null,(0,r.pI)(a,(a=>((0,r.uX)(),(0,r.CE)("td",{key:a.id,class:"menu-cell"},[(0,r.Lk)("div",{class:(0,m.C4)(["menu-item",{unavailable:!a.is_available}])},[(0,r.Lk)("div",R,[(0,r.Lk)("img",{src:i.getImageUrl(a.image),alt:a.name},null,8,j),a.is_available?(0,r.Q3)("",!0):((0,r.uX)(),(0,r.CE)("div",N,t[0]||(t[0]=[(0,r.Lk)("span",null,"Недоступно",-1)])))]),(0,r.Lk)("div",V,[(0,r.Lk)("h3",null,(0,m.v_)(a.name),1),(0,r.Lk)("p",U,(0,m.v_)(a.description),1),a.ingredients&&a.ingredients.length>0?((0,r.uX)(),(0,r.CE)("div",W,[t[1]||(t[1]=(0,r.Lk)("div",{class:"ingredients-header"},[(0,r.Lk)("span",{class:"ingredients-icon"},"🍳"),(0,r.Lk)("h4",null,"Ингредиенты")],-1)),(0,r.Lk)("div",z,[((0,r.uX)(!0),(0,r.CE)(r.FK,null,(0,r.pI)(a.ingredients,((e,t)=>((0,r.uX)(),(0,r.CE)("span",{key:e.id,class:"ingredient-tag"},[(0,r.eW)((0,m.v_)(e.name)+" ",1),t<a.ingredients.length-1?((0,r.uX)(),(0,r.CE)("span",K,"•")):(0,r.Q3)("",!0)])))),128))])])):(0,r.Q3)("",!0),(0,r.Lk)("div",D,[(0,r.Lk)("p",x,(0,m.v_)(a.price)+" ₽",1),(0,r.Lk)("button",{class:(0,m.C4)(["btn",{adding:n.addingToCart===a.id}]),disabled:!a.is_available||e.loading,onClick:e=>i.addItemToCart(a)},[n.addingToCart===a.id?((0,r.uX)(),(0,r.CE)("span",J,"Добавлено ✓")):((0,r.uX)(),(0,r.CE)("span",B,(0,m.v_)(a.is_available?"В корзину":"Недоступно"),1))],10,H)])])],2)])))),128))])))),128))])])):n.activeCategory&&0===i.activeItems.length?((0,r.uX)(),(0,r.CE)("div",G," В данной категории нет блюд ")):((0,r.uX)(),(0,r.CE)("div",Y," Выберите категорию меню "))])])}a(4114),a(8111),a(116);var ee=a(782),te={name:"MenuCategories",props:{categories:{type:Array,required:!0}},data(){return{activeCategory:null,columnsPerRow:3,addingToCart:null}},computed:{...(0,ee.aH)(["loading"]),activeItems(){if(!this.activeCategory)return[];const e=this.categories.find((e=>e.id===this.activeCategory));return e&&e.menu_items||[]},sortedActiveItems(){return[...this.activeItems].sort(((e,t)=>e.is_available===t.is_available?0:e.is_available?-1:1))},menuRows(){const e=[],t=this.sortedActiveItems;for(let a=0;a<t.length;a+=this.columnsPerRow)e.push(t.slice(a,a+this.columnsPerRow));return e}},methods:{...(0,ee.i0)(["addToCart"]),setActiveCategory(e){this.activeCategory=e},getImageUrl(e){return e||"/img/default-dish.jpg"},async addItemToCart(e){!1!==e.is_available&&(console.log("Добавление в корзину:",e.id),this.addToCart({menuItemId:e.id,quantity:1}),this.addingToCart=e.id,setTimeout((()=>{this.addingToCart===e.id&&(this.addingToCart=null)}),1500))},updateColumnsCount(){window.innerWidth<=768?this.columnsPerRow=1:window.innerWidth<=1200?this.columnsPerRow=2:this.columnsPerRow=3}},mounted(){this.categories.length>0&&(this.activeCategory=this.categories[0].id),window.addEventListener("resize",this.updateColumnsCount),this.updateColumnsCount()},beforeUnmount(){window.removeEventListener("resize",this.updateColumnsCount)}};const ae=(0,c.A)(te,[["render",Z],["__scopeId","data-v-6d3cc298"]]);var se=ae;const re={class:"cart-header"},ne={key:0,class:"cart-items"},ie={class:"cart-item-info"},oe={class:"cart-item-price"},ce={class:"cart-item-actions"},le=["onClick"],de={class:"quantity"},ue=["onClick"],me=["onClick"],pe={class:"cart-total"},ve={class:"total-price"},ye={key:1,class:"empty-cart"},he={key:0,class:"payment-modal-overlay"},Ce={class:"payment-modal"},be={class:"payment-modal-header"},ke={class:"payment-methods"},ge={class:"payment-modal-footer"},fe=["disabled"],Le={key:0,class:"cart-badge"};function Ie(e,t,a,s,n,i){const o=(0,r.g2)("OrderSuccess");return(0,r.uX)(),(0,r.CE)(r.FK,null,[(0,r.Lk)("div",{class:(0,m.C4)(["cart-sidebar",{"mobile-open":n.isMobileCartOpen}])},[(0,r.Lk)("div",re,[t[9]||(t[9]=(0,r.Lk)("h2",null,"Ваш заказ",-1)),n.isMobile?((0,r.uX)(),(0,r.CE)("button",{key:0,class:"close-cart-btn",onClick:t[0]||(t[0]=(...e)=>i.closeMobileCart&&i.closeMobileCart(...e))},"×")):(0,r.Q3)("",!0)]),e.cart.items.length>0?((0,r.uX)(),(0,r.CE)("div",ne,[((0,r.uX)(!0),(0,r.CE)(r.FK,null,(0,r.pI)(e.cart.items,(e=>((0,r.uX)(),(0,r.CE)("div",{key:e.id,class:"cart-item"},[(0,r.Lk)("div",ie,[(0,r.Lk)("h3",null,(0,m.v_)(e.name||"Неизвестное блюдо"),1),(0,r.Lk)("p",oe,(0,m.v_)(e.price||0)+" ₽ × "+(0,m.v_)(e.quantity),1)]),(0,r.Lk)("div",ce,[(0,r.Lk)("button",{class:"btn-small",onClick:t=>i.decreaseQuantity(e.id)},"-",8,le),(0,r.Lk)("span",de,(0,m.v_)(e.quantity),1),(0,r.Lk)("button",{class:"btn-small",onClick:t=>i.increaseQuantity(e.id)},"+",8,ue),(0,r.Lk)("button",{class:"btn-remove",onClick:t=>i.removeItem(e.id)},"×",8,me)])])))),128)),(0,r.Lk)("div",pe,[t[10]||(t[10]=(0,r.Lk)("p",null,"Итого:",-1)),(0,r.Lk)("p",ve,(0,m.v_)(e.cartTotal)+" ₽",1)]),(0,r.Lk)("button",{class:"btn-checkout",onClick:t[1]||(t[1]=(...e)=>i.showPaymentModal&&i.showPaymentModal(...e))},"Оформить заказ")])):((0,r.uX)(),(0,r.CE)("div",ye,t[11]||(t[11]=[(0,r.Lk)("p",null,"Ваша корзина пуста",-1),(0,r.Lk)("p",null,"Добавьте блюда из меню",-1)])))],2),n.isPaymentModalVisible?((0,r.uX)(),(0,r.CE)("div",he,[(0,r.Lk)("div",Ce,[(0,r.Lk)("div",be,[t[12]||(t[12]=(0,r.Lk)("h3",null,"Выберите способ оплаты",-1)),(0,r.Lk)("button",{class:"close-modal-btn",onClick:t[2]||(t[2]=(...e)=>i.hidePaymentModal&&i.hidePaymentModal(...e))},"×")]),(0,r.Lk)("div",ke,[(0,r.Lk)("div",{class:(0,m.C4)(["payment-method-item",{selected:"card"===n.selectedPaymentMethod}]),onClick:t[3]||(t[3]=e=>i.selectPaymentMethod("card"))},t[13]||(t[13]=[(0,r.Lk)("div",{class:"payment-method-icon"},"💳",-1),(0,r.Lk)("div",{class:"payment-method-name"},"Банковская карта",-1)]),2),(0,r.Lk)("div",{class:(0,m.C4)(["payment-method-item",{selected:"cash"===n.selectedPaymentMethod}]),onClick:t[4]||(t[4]=e=>i.selectPaymentMethod("cash"))},t[14]||(t[14]=[(0,r.Lk)("div",{class:"payment-method-icon"},"💵",-1),(0,r.Lk)("div",{class:"payment-method-name"},"Наличные",-1)]),2),(0,r.Lk)("div",{class:(0,m.C4)(["payment-method-item",{selected:"sbp"===n.selectedPaymentMethod}]),onClick:t[5]||(t[5]=e=>i.selectPaymentMethod("sbp"))},t[15]||(t[15]=[(0,r.Lk)("div",{class:"payment-method-icon"},"📱",-1),(0,r.Lk)("div",{class:"payment-method-name"},"СБП",-1)]),2)]),(0,r.Lk)("div",ge,[(0,r.Lk)("button",{class:"btn-confirm-payment",disabled:!n.selectedPaymentMethod,onClick:t[6]||(t[6]=(...e)=>i.confirmPayment&&i.confirmPayment(...e))}," Подтвердить ",8,fe)])])])):(0,r.Q3)("",!0),(0,r.bF)(o,{visible:n.isOrderSuccessVisible,"order-id":n.lastOrderId,"order-items":e.cart.items,"order-total":e.cartTotal,"payment-method":n.selectedPaymentMethod,"payment-status":"paid",onClose:i.hideOrderSuccess},null,8,["visible","order-id","order-items","order-total","payment-method","onClose"]),n.isMobile?((0,r.uX)(),(0,r.CE)("div",{key:1,class:"mobile-cart-icon",onClick:t[7]||(t[7]=(...e)=>i.openMobileCart&&i.openMobileCart(...e))},[t[16]||(t[16]=(0,r.Lk)("div",{class:"cart-icon"},"🛒",-1)),e.cartItemCount>0?((0,r.uX)(),(0,r.CE)("div",Le,(0,m.v_)(e.cartItemCount),1)):(0,r.Q3)("",!0)])):(0,r.Q3)("",!0),n.isMobile&&n.isMobileCartOpen?((0,r.uX)(),(0,r.CE)("div",{key:2,class:"mobile-cart-overlay",onClick:t[8]||(t[8]=(...e)=>i.closeMobileCart&&i.closeMobileCart(...e))})):(0,r.Q3)("",!0)],64)}const Me={key:0,class:"order-success-overlay"},_e={class:"order-success-modal"},Ee={class:"order-success-header"},Se={class:"order-success-content"},we={class:"payment-info"},Pe={class:"order-items"},Te={class:"order-item-name"},Xe={class:"order-item-quantity"},Oe={class:"order-item-price"},qe={class:"order-total"},Ae={class:"total-price"},Fe={class:"order-success-footer"};function $e(e,t,a,s,n,i){return a.visible?((0,r.uX)(),(0,r.CE)("div",Me,[(0,r.Lk)("div",_e,[(0,r.Lk)("div",Ee,[t[2]||(t[2]=(0,r.Lk)("h2",null,"Заказ успешно оформлен!",-1)),(0,r.Lk)("button",{class:"close-success-btn",onClick:t[0]||(t[0]=(...e)=>i.close&&i.close(...e))},"×")]),(0,r.Lk)("div",Se,[t[7]||(t[7]=(0,r.Lk)("div",{class:"success-icon"},"✓",-1)),t[8]||(t[8]=(0,r.Lk)("p",{class:"success-message"},"Ваш заказ принят и передан на кухню",-1)),t[9]||(t[9]=(0,r.Lk)("p",{class:"cooking-time"},"Примерное время приготовления: 15-20 минут",-1)),(0,r.Lk)("div",we,[(0,r.Lk)("p",null,[t[3]||(t[3]=(0,r.eW)("Способ оплаты: ")),(0,r.Lk)("span",null,(0,m.v_)(i.getPaymentMethodName(a.paymentMethod)),1)]),(0,r.Lk)("p",null,[t[4]||(t[4]=(0,r.eW)("Статус оплаты: ")),(0,r.Lk)("span",null,(0,m.v_)("paid"===a.paymentStatus?"Оплачено":"Ожидает оплаты"),1)])]),(0,r.Lk)("div",Pe,[t[6]||(t[6]=(0,r.Lk)("h3",null,"Состав заказа:",-1)),((0,r.uX)(!0),(0,r.CE)(r.FK,null,(0,r.pI)(a.orderItems,(e=>((0,r.uX)(),(0,r.CE)("div",{class:"order-item",key:e.id},[(0,r.Lk)("div",Te,(0,m.v_)(e.name),1),(0,r.Lk)("div",Xe,(0,m.v_)(e.quantity)+" шт.",1),(0,r.Lk)("div",Oe,(0,m.v_)(e.price*e.quantity)+" ₽",1)])))),128)),(0,r.Lk)("div",qe,[t[5]||(t[5]=(0,r.Lk)("div",null,"Итого:",-1)),(0,r.Lk)("div",Ae,(0,m.v_)(a.orderTotal)+" ₽",1)])])]),(0,r.Lk)("div",Fe,[(0,r.Lk)("button",{class:"btn-continue",onClick:t[1]||(t[1]=(...e)=>i.close&&i.close(...e))},"Продолжить")])])])):(0,r.Q3)("",!0)}var Qe={name:"OrderSuccess",props:{visible:{type:Boolean,default:!1},orderId:{type:[Number,String],default:null},orderItems:{type:Array,default:()=>[]},orderTotal:{type:Number,default:0},paymentMethod:{type:String,default:"card"},paymentStatus:{type:String,default:"pending"}},methods:{close(){this.$emit("close")},getPaymentMethodName(e){const t={card:"Банковская карта",cash:"Наличные",sbp:"СБП"};return t[e]||e}}};const Re=(0,c.A)(Qe,[["render",$e],["__scopeId","data-v-41f1b7f5"]]);var je=Re,Ne={name:"CartSidebar",components:{OrderSuccess:je},data(){return{isMobile:!1,isMobileCartOpen:!1,isPaymentModalVisible:!1,isOrderSuccessVisible:!1,selectedPaymentMethod:null,lastOrderId:null}},computed:{...(0,ee.aH)(["cart"]),...(0,ee.L8)(["cartTotal","cartItemCount"])},methods:{...(0,ee.i0)(["updateCartItem","removeFromCart","clearCart","checkout"]),increaseQuantity(e){const t=this.cart.items.find((t=>t.id===e));t&&this.updateCartItem({itemId:e,quantity:t.quantity+1})},decreaseQuantity(e){const t=this.cart.items.find((t=>t.id===e));t&&(t.quantity>1?this.updateCartItem({itemId:e,quantity:t.quantity-1}):this.removeItem(e))},removeItem(e){this.removeFromCart(e)},showPaymentModal(){this.isPaymentModalVisible=!0,this.selectedPaymentMethod=null,document.body.style.overflow="hidden"},hidePaymentModal(){this.isPaymentModalVisible=!1,document.body.style.overflow=""},selectPaymentMethod(e){this.selectedPaymentMethod=e},async confirmPayment(){if(this.selectedPaymentMethod)try{const e=await this.checkout({paymentMethod:this.selectedPaymentMethod});e&&e.success?(this.lastOrderId=e.orderId,this.hidePaymentModal(),this.showOrderSuccess(),this.isMobile&&this.closeMobileCart()):(alert(e&&e.error||"Ошибка при оформлении заказа"),this.hidePaymentModal())}catch(e){console.error("Ошибка при оформлении заказа:",e),alert("Произошла ошибка при оформлении заказа"),this.hidePaymentModal()}},showOrderSuccess(){this.isOrderSuccessVisible=!0,document.body.style.overflow="hidden"},hideOrderSuccess(){this.isOrderSuccessVisible=!1,document.body.style.overflow=""},openMobileCart(){this.isMobileCartOpen=!0,document.body.style.overflow="hidden"},closeMobileCart(){this.isMobileCartOpen=!1,document.body.style.overflow=""},checkScreenSize(){this.isMobile=window.innerWidth<768}},mounted(){this.checkScreenSize(),window.addEventListener("resize",this.checkScreenSize)},beforeUnmount(){window.removeEventListener("resize",this.checkScreenSize),(this.isMobileCartOpen||this.isPaymentModalVisible||this.isOrderSuccessVisible)&&(document.body.style.overflow="")}};const Ve=(0,c.A)(Ne,[["render",Ie],["__scopeId","data-v-3c713274"]]);var Ue=Ve,We={name:"TableView",components:{RestaurantHeader:O,MenuCategories:se,CartSidebar:Ue},props:{id:{type:[String,Number],required:!0}},computed:{loading(){return this.$store.state.loading},error(){return this.$store.state.error},table(){return this.$store.state.table||{}},restaurant(){return this.$store.state.restaurant||{}},menuCategories(){return this.$store.state.menuCategories||[]},cart(){return this.$store.state.cart||{items:[],total:0}}},methods:{retryLoading(){this.loadTableData()},loadTableData(){this.$store.dispatch("loadTableData",this.id)},addToCart(e,t=1){this.$store.dispatch("addToCart",{menuItemId:e,quantity:t})},updateCartItem(e,t){this.$store.dispatch("updateCartItem",{cartItemId:e,quantity:t})},removeCartItem(e){this.$store.dispatch("removeCartItem",e)},clearCart(){this.$store.dispatch("clearCart")}},created(){this.loadTableData()},watch:{id(){this.loadTableData()}}};const ze=(0,c.A)(We,[["render",b],["__scopeId","data-v-5f4c7f9c"]]);var Ke=ze;const De={class:"not-found"},xe={class:"container"};function He(e,t,a,s,n,i){const o=(0,r.g2)("router-link");return(0,r.uX)(),(0,r.CE)("div",De,[(0,r.Lk)("div",xe,[t[1]||(t[1]=(0,r.Lk)("h1",null,"404",-1)),t[2]||(t[2]=(0,r.Lk)("h2",null,"Страница не найдена",-1)),t[3]||(t[3]=(0,r.Lk)("p",null,"Извините, запрашиваемая страница не существует.",-1)),(0,r.bF)(o,{to:"/",class:"btn"},{default:(0,r.k6)((()=>t[0]||(t[0]=[(0,r.eW)("Вернуться на главную")]))),_:1})])])}var Je={name:"NotFound"};const Be=(0,c.A)(Je,[["render",He],["__scopeId","data-v-4c6c8664"]]);var Ge=Be;const Ye=[{path:"/",redirect:"/table/1"},{path:"/table/:id",name:"Table",component:Ke,props:!0},{path:"/:pathMatch(.*)*",name:"NotFound",component:Ge}],Ze=(0,u.aE)({history:(0,u.LA)("/"),routes:Ye});var et=Ze,tt=(a(2489),a(1701),a(8237),a(4373));const at="http://127.0.0.1:8000/api",st=localStorage.getItem("cart"),rt=st?JSON.parse(st):{items:[],total:0},nt=localStorage.getItem("sessionId");var it=(0,ee.y$)({state:{sessionId:nt||null,tableId:localStorage.getItem("tableId")||null,restaurant:null,menuCategories:[],cart:rt,loading:!1,error:null},getters:{cartItemCount(e){return e.cart.items.reduce(((e,t)=>e+(t.quantity||0)),0)},cartTotal(e){return e.cart.items.reduce(((e,t)=>e+(t.price||0)*(t.quantity||0)),0)},isSessionActive(e){return!!e.sessionId}},mutations:{setSessionId(e,t){e.sessionId=t,t?localStorage.setItem("sessionId",t):localStorage.removeItem("sessionId")},setTableId(e,t){e.tableId=t,t?localStorage.setItem("tableId",t):localStorage.removeItem("tableId")},setTable(e,t){e.table=t},setRestaurant(e,t){e.restaurant=t},setMenuCategories(e,t){e.menuCategories=t},addToCart(e,{menuItem:t,quantity:a=1}){const s=e.cart.items.find((e=>e.id===t.id));s?s.quantity+=a:e.cart.items.push({id:t.id,name:t.name,price:t.price,image:t.image,quantity:a}),e.cart.total=e.cart.items.reduce(((e,t)=>e+t.price*t.quantity),0),localStorage.setItem("cart",JSON.stringify(e.cart))},updateCartItem(e,{itemId:t,quantity:a}){const s=e.cart.items.find((e=>e.id===t));s&&(s.quantity=a,e.cart.total=e.cart.items.reduce(((e,t)=>e+t.price*t.quantity),0),localStorage.setItem("cart",JSON.stringify(e.cart)))},removeFromCart(e,t){e.cart.items=e.cart.items.filter((e=>e.id!==t)),e.cart.total=e.cart.items.reduce(((e,t)=>e+t.price*t.quantity),0),localStorage.setItem("cart",JSON.stringify(e.cart))},clearCart(e){e.cart={items:[],total:0},localStorage.removeItem("cart")},setLoading(e,t){e.loading=t},setError(e,t){e.error=t}},actions:{async createSession({commit:e},t){try{e("setLoading",!0);const a=await tt.A.post(`${at}/session/create`,{table_id:t});return a.data&&a.data.session_id?(e("setSessionId",a.data.session_id),e("setTableId",t),e("setError",null),{success:!0,sessionId:a.data.session_id}):(e("setError","Не удалось создать сессию"),{success:!1,error:"Не удалось создать сессию"})}catch(a){console.error("Ошибка при создании сессии:",a);let t="Ошибка при создании сессии";return a.response&&a.response.data&&a.response.data.error&&(t=a.response.data.error),e("setError",t),{success:!1,error:t}}finally{e("setLoading",!1)}},async loadTableData({commit:e,state:t,dispatch:a},s){try{if(e("setLoading",!0),e("setTableId",s),!t.sessionId){const e=await a("createSession",s);if(!e.success)return{success:!1,error:e.error}}const r=await tt.A.get(`${at}/tables/${s}`);return e("setRestaurant",r.data.restaurant),e("setMenuCategories",r.data.menu_categories),e("setTable",r.data.table),e("setError",null),{success:!0}}catch(r){console.error("Ошибка при загрузке данных стола:",r);let t="Ошибка при загрузке данных стола";return r.response&&r.response.data&&r.response.data.error&&(t=r.response.data.error),e("setError",t),{success:!1,error:t}}finally{e("setLoading",!1)}},addToCart({commit:e,state:t},{menuItemId:a,quantity:s=1}){let r=null;for(const n of t.menuCategories){const e=n.menu_items.find((e=>e.id===a));if(e){r=e;break}}r&&e("addToCart",{menuItem:r,quantity:s})},updateCartItem({commit:e},{itemId:t,quantity:a}){e("updateCartItem",{itemId:t,quantity:a})},removeFromCart({commit:e},t){e("removeFromCart",t)},clearCart({commit:e}){e("clearCart")},async checkout({state:e,commit:t},{paymentMethod:a="card"}={}){try{if(t("setLoading",!0),!e.sessionId)return t("setError","Нет активной сессии"),{success:!1,error:"Нет активной сессии"};const s={session_id:e.sessionId,payment_method:a,items:e.cart.items.map((e=>({id:e.id,quantity:e.quantity,price:e.price})))},r=await tt.A.post(`${at}/orders/create`,s);return r.data&&r.data.order_id?(t("clearCart"),t("setError",null),{success:!0,orderId:r.data.order_id}):(t("setError","Не удалось оформить заказ"),{success:!1,error:"Не удалось оформить заказ"})}catch(s){console.error("Ошибка при оформлении заказа:",s);let e="Ошибка при оформлении заказа";return s.response&&s.response.data&&s.response.data.error&&(e=s.response.data.error),t("setError",e),{success:!1,error:e}}finally{t("setLoading",!1)}}}});tt.A.defaults.baseURL="http://127.0.0.1:8000/api",(0,s.Ef)(d).use(et).use(it).mount("#app")}},t={};function a(s){var r=t[s];if(void 0!==r)return r.exports;var n=t[s]={exports:{}};return e[s].call(n.exports,n,n.exports,a),n.exports}a.m=e,function(){var e=[];a.O=function(t,s,r,n){if(!s){var i=1/0;for(d=0;d<e.length;d++){s=e[d][0],r=e[d][1],n=e[d][2];for(var o=!0,c=0;c<s.length;c++)(!1&n||i>=n)&&Object.keys(a.O).every((function(e){return a.O[e](s[c])}))?s.splice(c--,1):(o=!1,n<i&&(i=n));if(o){e.splice(d--,1);var l=r();void 0!==l&&(t=l)}}return t}n=n||0;for(var d=e.length;d>0&&e[d-1][2]>n;d--)e[d]=e[d-1];e[d]=[s,r,n]}}(),function(){a.n=function(e){var t=e&&e.__esModule?function(){return e["default"]}:function(){return e};return a.d(t,{a:t}),t}}(),function(){a.d=function(e,t){for(var s in t)a.o(t,s)&&!a.o(e,s)&&Object.defineProperty(e,s,{enumerable:!0,get:t[s]})}}(),function(){a.g=function(){if("object"===typeof globalThis)return globalThis;try{return this||new Function("return this")()}catch(e){if("object"===typeof window)return window}}()}(),function(){a.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)}}(),function(){a.r=function(e){"undefined"!==typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})}}(),function(){var e={524:0};a.O.j=function(t){return 0===e[t]};var t=function(t,s){var r,n,i=s[0],o=s[1],c=s[2],l=0;if(i.some((function(t){return 0!==e[t]}))){for(r in o)a.o(o,r)&&(a.m[r]=o[r]);if(c)var d=c(a)}for(t&&t(s);l<i.length;l++)n=i[l],a.o(e,n)&&e[n]&&e[n][0](),e[n]=0;return a.O(d)},s=self["webpackChunkmy_project"]=self["webpackChunkmy_project"]||[];s.forEach(t.bind(null,0)),s.push=t.bind(null,s.push.bind(s))}();var s=a.O(void 0,[504],(function(){return a(1504)}));s=a.O(s)})();
//# sourceMappingURL=app.e55ddfdf.js.map