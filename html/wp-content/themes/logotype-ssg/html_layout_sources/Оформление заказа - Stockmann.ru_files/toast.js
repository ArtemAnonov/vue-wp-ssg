M = window.M || {};
(function (anim) {
  // Значения по умолчанию
  let _defaults = {
    html: "",
    displayLength: 0,
    inDuration: 300,
    outDuration: 375,
    classes: "",
    completeCallback: null,
    clickCallback: null,
    activationPercent: 0.8,
  };

  class Toast {
    constructor(options) {
      /**
       * Опции для Toast
       * @member Toast#options
       */
      this.options = { ...Toast.defaults, ...options };
      this.message = this.options.html;
      this.clickCallback = this.options.clickCallback;

      /**
       * Время до удаления Toast
       */
      this.timeRemaining = this.options.displayLength;

      // Создаем контейнер Toast
      if (Toast._toasts.length === 0) {
        Toast._createContainer();
      }

      // Создаем новый Toast
      Toast._toasts.push(this);
      let toastElement = this._createToast();
      toastElement.M_Toast = this;
      this.el = toastElement;
      this._animateIn();

      this._removeContainerByClick();
    }
    // Получаем значения по дефолту
    static get defaults() {
      return _defaults;
    }

    /**
     * Создания контейнера с классом и id
     */
    static _createContainer() {
      let container = document.createElement("div");
      container.setAttribute("id", "toast-container");

      document.body.appendChild(container);
      Toast._container = container;
    }

    /**
     * Удаление контейнера
     */
    static _removeContainer() {
      Toast._container = null;
    }

    /**
     * Удаление контейнера
     */
    _removeContainerByClick() {
      const self = this;
      document.querySelector("#toast-container").addEventListener("click", function () {
        if (self.clickCallback) self.clickCallback();
        self.dismiss();
        this.remove();
      });
    }

    /**
     * Удалить все Toasts
     */
    static dismissAll() {
      for (let toastIndex in Toast._toasts) {
        Toast._toasts[toastIndex].dismiss();
      }
    }

    /**
     * Создаем Toast
     */
    _createToast() {
      let toast = document.createElement("div");
      toast.classList.add("toast");

      // Set content
      if (
        typeof HTMLElement === "object"
          ? this.message instanceof HTMLElement
          : this.message &&
            typeof this.message === "object" &&
            this.message !== null &&
            this.message.nodeType === 1 &&
            typeof this.message.nodeName === "string"
      ) {
        toast.appendChild();
        toast.appendChild(this.message);

        // Check if it is jQuery object
      } else {
        toast.innerHTML = this.message;
      }

      // Append toasft
      Toast._container.appendChild(toast);
      return toast;
    }

    /**
     * Анимация для Toast
     */
    _animateIn() {
      // Animate toast in
      anim({
        targets: this.el,
        top: "50%",
        opacity: 1,
        duration: this.options.inDuration,
        easing: "easeOutCubic",
      });
    }

    /**
     * Создаем setInterval, который автоматически удалит toast, когда timeRemaining >= 0
     */
    _setTimer() {
      if (this.timeRemaining !== Infinity) {
        this.counterInterval = setInterval(() => {
          // уменьшаем интервал
          this.timeRemaining -= 20;

          // Animate toast out
          if (this.timeRemaining <= 0) {
            this.dismiss();
          }
        }, 20);
      }
    }

    /**
     * Отключить toast с анимациец
     */
    dismiss() {
      window.clearInterval(this.counterInterval);

      anim({
        targets: this.el,
        opacity: 0,
        marginTop: -40,
        duration: this.options.outDuration,
        easing: "easeOutExpo",
        complete: () => {
          // Call the optional callback
          if (typeof this.options.completeCallback === "function") {
            this.options.completeCallback();
          }
          // Remove toast from DOM
          this.el.remove();
          Toast._toasts.splice(Toast._toasts.indexOf(this), 1);
          if (Toast._toasts.length === 0) {
            Toast._removeContainer();
          }
        },
      });
    }
  }

  /**
   * @static
   * @memberof Toast
   * @type {Array.<Toast>}
   */
  Toast._toasts = [];

  /**
   * @static
   * @memberof Toast
   */
  Toast._container = null;

  M.Toast = Toast;
  M.toast = function (options) {
    return new Toast(options);
  };
})(M.anime);
