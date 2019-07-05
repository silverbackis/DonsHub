<template>
  <component :is="domTag" v-if="isExternal" v-bind="linkProps">
    <slot></slot>
  </component>
  <nuxt-link v-else v-bind="linkProps">
    <slot></slot>
  </nuxt-link>
</template>

<script>
export default {
  props: {
    to: {
      type: String,
      required: true
    },
    tag: {
      type: String,
      required: false,
      default: null
    },
    activeClass: {
      type: String,
      default: 'is-active'
    },
    exact: {
      type: Boolean,
      default: false
    }
  },
  computed: {
    component() {
      return this.isExternal
    },
    isExternal() {
      return this.to.match(/^(http(s)?|ftp):\/\//)
    },
    linkProps() {
      if (this.isExternal) {
        return Object.assign({}, this.$props, {
          href: this.to,
          target: '_blank',
          rel: 'noopener'
        })
      }
      return Object.assign({}, this.$props, {
        tag: this.domTag,
        to: this.to
      })
    },
    domTag() {
      return this.tag || 'a'
    }
  }
}
</script>
