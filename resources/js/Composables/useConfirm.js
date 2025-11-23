import { ref } from "vue";

export function useConfirm() {
    const isVisible = ref(false);
    const resolvePromise = ref(null);
    const title = ref("");
    const message = ref("");

    const confirm = (confirmTitle, confirmMessage) => {
        title.value = confirmTitle;
        message.value = confirmMessage;
        isVisible.value = true;

        return new Promise((resolve) => {
            resolvePromise.value = resolve;
        });
    };

    const accept = () => {
        isVisible.value = false;
        if (resolvePromise.value) {
            resolvePromise.value(true);
        }
    };

    const reject = () => {
        isVisible.value = false;
        if (resolvePromise.value) {
            resolvePromise.value(false);
        }
    };

    return {
        isVisible,
        title,
        message,
        confirm,
        accept,
        reject,
    };
}
