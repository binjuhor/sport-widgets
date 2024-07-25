// import { defineCustomElement } from 'vue';
// import WidgetLoader from '@/components/WidgetLoader.vue';

import { defineCustomElement } from 'vue'
import WidgetLoader from '@/components/WidgetLoader.vue'

function loadVueAndDefineComponents() {
  alert('aaa');
  if (typeof window.Vue === 'undefined') {
    const script = document.createElement('script');
    script.src = 'https://unpkg.com/vue@3';
    script.type = 'module';
    script.async = true;

    script.onload = () => {
      defineCustomElements();
    };

    script.onerror = () => {
      console.error('Error loading Vue.js');
      // Optionally, provide fallback or error message to the user
    };

    document.head.appendChild(script);
  } else {
    defineCustomElements();
  }
}

function defineCustomElements() {
  const widgetLoader = defineCustomElement(WidgetLoader);
  window.customElements.define('widget-loader', widgetLoader);
}

loadVueAndDefineComponents();