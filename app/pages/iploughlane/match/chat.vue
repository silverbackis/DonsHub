<template>
  <div class="chat-page">
    <div
      class="columns flex-direction-column is-gapless is-mobile chat-page-vcolumns"
    >
      <div class="column">
        <ul v-if="chatMessages && chatMessages.length" ref="chatList">
          <chat-message
            v-for="currentMessage in chatMessages"
            :key="currentMessage"
            :message="currentMessage"
          />
          <li v-if="loadingMore" class="has-text-centered load-more-list-item">
            <div class="loader is-medium is-primary">
              <span class="is-sr-only">Loading</span>
            </div>
          </li>
        </ul>
        <section v-else class="section has-text-centered">
          <div v-if="chatMessages === null">
            <div class="loader is-medium is-primary">
              <span class="is-sr-only">Loading</span>
            </div>
          </div>
          <div v-else>
            <p class="subtitle">
              Enter a message to start the conversation in today's game.
            </p>
          </div>
        </section>
      </div>
      <div class="column is-narrow chat-field-row">
        <div class="chat-field">
          <div class="container">
            <div class="field">
              <div class="control has-icons-right">
                <input
                  ref="messageInput"
                  v-model="message"
                  :disabled="posting"
                  class="input is-medium"
                  placeholder="Enter message"
                  maxlength="500"
                  @keypress.enter="postMessage"
                />
                <a
                  class="icon is-right send-message-button"
                  @click="postMessage"
                >
                  <span class="is-sr-only">Send message</span>
                </a>
                <field-errors :errors="errors" />
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { mapGetters } from 'vuex'
import HandleErrorMixin from '~/components/HandleErrorMixin'
import FieldErrors from '~/components/FieldErrors'
import MercureMixin from '~/components/MercureMixin'
import ChatMessage from '~/components/iploughlane/ChatMessage'

export default {
  components: { ChatMessage, FieldErrors },
  mixins: [HandleErrorMixin, MercureMixin],
  data() {
    return {
      message: '',
      posting: false,
      errors: null,
      chatMessages: null,
      loadingMore: false,
      chatMessagesEndpoint: null,
      displayingOldest: false
    }
  },
  computed: {
    ...mapGetters({
      match: 'currentMatch'
    })
  },
  async mounted() {
    if (!this.match) {
      this.chatMessages = []
      return
    }

    const searchParams = {
      match: this.match['@id']
    }
    const qs = Object.keys(searchParams)
      .map(key => {
        return (
          encodeURIComponent(key) + '=' + encodeURIComponent(searchParams[key])
        )
      })
      .join('&')

    this.chatMessagesEndpoint = '/chat_messages?' + qs
    const { data } = await this.$axios.get(this.chatMessagesEndpoint)

    this.processMessagesData(data)

    this.mercureMount(['/chat_messages/{id}'])
    this.eventSource.onmessage = this.mercureMessage

    this.$nextTick(() => {
      if (this.$refs.messageInput) {
        this.$refs.messageInput.focus()
      }
    })
    this.addScrollListener()
  },
  beforeDestroy() {
    this.removeScrollListener()
  },
  methods: {
    processMessagesData(messagesData) {
      const messages = {}
      messagesData['hydra:member'].forEach(message => {
        messages[message['@id']] = message
      })
      this.$bwstarter.setEntities(messages)
      const messageIds = Object.keys(messages)
      if (this.chatMessages === null) {
        this.chatMessages = []
      }
      if (
        !messageIds.length ||
        messageIds[messageIds.length - 1] ===
          this.chatMessages[this.chatMessages.length - 1]
      ) {
        this.displayingOldest = true
      }
      this.chatMessages.push(...messageIds)
    },
    async postMessage() {
      this.posting = true
      this.errors = null
      try {
        await this.$axios.post('/chat_messages', {
          message: this.message
        })
      } catch (newMessageError) {
        this.errors = this.getErrorMessages(newMessageError)
      }
      this.message = ''
      this.posting = false
      this.$nextTick(() => {
        if (this.$refs.messageInput) {
          this.$refs.messageInput.focus()
        }
        const chatList = this.$refs.chatList
        if (chatList) {
          const offset = 80
          const chatListTop = chatList.getBoundingClientRect().top
          if (chatListTop < offset) {
            this.$gsap.tween.to(window, 0.3, {
              scrollTo: { y: chatList, offsetY: offset }
            })
          }
        }
      })
    },
    mercureMessage({ data: json }) {
      const data = this.receiveEntityData({ data: json })
      if (this.chatMessages.indexOf(data['@id']) === -1) {
        this.chatMessages.unshift(data['@id'])
      }
    },
    addScrollListener() {
      window.addEventListener('scroll', this.loadMoreScrollListener)
    },
    removeScrollListener() {
      window.removeEventListener('scroll', this.loadMoreScrollListener)
    },
    async loadMoreScrollListener() {
      const chatList = this.$refs.chatList
      if (chatList) {
        const chatListBottom = chatList.getBoundingClientRect().bottom
        const windowHeight = window.clientHeight || window.innerHeight
        const bottomBelowWindow = chatListBottom - windowHeight
        if (
          bottomBelowWindow < 0 &&
          !this.loadingMore &&
          !this.displayingOldest
        ) {
          await this.loadMore()
        }
      }
    },
    async loadMore() {
      if (!this.chatMessagesEndpoint) {
        return
      }
      this.loadingMore = true
      const oldestMessage = this.getEntity(
        this.chatMessages[this.chatMessages.length - 1]
      )
      const { data } = await this.$axios.get(
        this.chatMessagesEndpoint +
          '&created[strictly_before]=' +
          encodeURIComponent(oldestMessage.created)
      )
      this.processMessagesData(data)
      this.loadingMore = false
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
  padding-top: 3rem
  .chat-page-vcolumns
    position: relative
    height: 100%
  .chat-field-row
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
  .load-more-list-item
    padding: 1rem
</style>
