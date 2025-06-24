// toastService.js
import { ElMessage } from 'element-plus';

/**
 * Display a toast message.
 * @param {string} message - Message to be displayed.
 * @param {string} type - Type of the message ('success', 'warning', 'info', 'error').
 * @param {number} duration - Duration for which the toast is visible (in milliseconds), defaults to 3000.
 */
export function showToast(message, type = 'info', duration = 3000) {
    ElMessage({
        message: message,
        type: type,
        showClose: true,
        duration: duration,
        customClass: 'el-message-right'
    });
}

export default {
    install(app, options) {
        app.config.globalProperties.$toast = showToast;
    }
};
