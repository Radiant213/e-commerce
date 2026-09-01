/**
 * Dynamic loader and trigger for MidTrans Snap JS in Sandbox/Production
 */
const MIDTRANS_SNAP_URL = 'https://app.sandbox.midtrans.com/snap/snap.js';
const MIDTRANS_CLIENT_KEY = import.meta.env.VITE_MIDTRANS_CLIENT_KEY || '';

let isScriptLoaded = false;

export const loadMidtransScript = () => {
  return new Promise((resolve, reject) => {
    if (window.snap) {
      resolve(window.snap);
      return;
    }

    if (isScriptLoaded) {
      resolve(window.snap);
      return;
    }

    const script = document.createElement('script');
    script.src = MIDTRANS_SNAP_URL;
    script.setAttribute('data-client-key', MIDTRANS_CLIENT_KEY);
    script.async = true;

    script.onload = () => {
      isScriptLoaded = true;
      resolve(window.snap);
    };

    script.onerror = (err) => {
      reject(new Error('Gagal memuat script pembayaran MidTrans Snap: ' + err.message));
    };

    document.body.appendChild(script);
  });
};

export const payWithSnap = async (snapToken, callbacks = {}) => {
  const snap = await loadMidtransScript();

  if (!snap) {
    throw new Error('MidTrans Snap instance tidak ditemukan.');
  }

  window.snap.pay(snapToken, {
    onSuccess: (result) => {
      if (callbacks.onSuccess) callbacks.onSuccess(result);
    },
    onPending: (result) => {
      if (callbacks.onPending) callbacks.onPending(result);
    },
    onError: (result) => {
      if (callbacks.onError) callbacks.onError(result);
    },
    onClose: () => {
      if (callbacks.onClose) callbacks.onClose();
    },
  });
};

export default { loadMidtransScript, payWithSnap };
