import { defineCustomElement } from 'vue'
import WidgetLoader from '@/components/NormalWidget.vue'

const widgetLoader = defineCustomElement(WidgetLoader);
window.customElements.define('widget-loader', widgetLoader);
