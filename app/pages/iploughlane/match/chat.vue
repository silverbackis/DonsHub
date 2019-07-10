<template>
  <div class="chat-page">
    <div
      class="columns flex-direction-column is-gapless is-mobile chat-page-vcolumns"
    >
      <div class="column">
        <ul>
          <li class="chat-row">
            <div class="container">
              <div class="columns is-mobile">
                <div class="column is-narrow">
                  Avatar
                </div>
                <div class="column">
                  Name<br />
                  Message
                </div>
              </div>
            </div>
          </li>
        </ul>
      </div>
      <div class="column is-narrow chat-row">
        <div class="chat-field">
          <div class="container">
            <div class="field">
              <div class="control has-icons-right">
                <input
                  v-model="message"
                  class="input is-medium"
                  placeholder="Enter message"
                  @keypress.enter="postMessage"
                />
                <a
                  class="icon is-right send-message-button"
                  @click="postMessage"
                >
                  <span class="is-sr-only">Send message</span>
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      message: ''
    }
  },
  methods: {
    postMessage() {
      // eslint-disable-next-line no-console
      console.log(this.message)
    }
  },
  head: {
    title: 'Chat'
  }
}
</script>

<style lang="sass">
@import "assets/sass/utilities"
.chat-page
  .chat-page-vcolumns
    position: relative
    height: 100%
  .chat-row
    position: sticky
    bottom: 0
    .chat-field
      background: $white
      padding: 1rem
      .icon.is-right
        pointer-events: auto
    .send-message-button
      z-index: 2
      cursor: pointer
      background: url('~assets/images/icon-send.svg') 50% 50% no-repeat
</style>
