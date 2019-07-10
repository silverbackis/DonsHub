<template>
  <li class="chat-message-row">
    <div class="container">
      <div class="columns is-mobile chat-message">
        <div v-if="avatarData" class="column is-narrow">
          <img
            class="avatar"
            :src="'/avatars/' + avatarData.image"
            :alt="avatarData.description"
          />
        </div>
        <div class="column">
          <p class="username">
            {{ messageData.chatUser.username }}
          </p>
          <p class="message">
            {{ messageData.message }}
          </p>
          <p class="created help has-text-grey-light">
            {{ dateTime }}
          </p>
        </div>
      </div>
    </div>
  </li>
</template>

<script>
import { mapState } from 'vuex'
import moment from 'moment'

export default {
  props: {
    message: {
      type: String,
      required: true
    }
  },
  data() {
    return {
      dateTime: null,
      dateTimeRefreshTimeout: null
    }
  },
  computed: {
    ...mapState({
      avatars: 'avatars'
    }),
    messageData() {
      return this.getEntity(this.message)
    },
    avatarData() {
      return this.avatars[this.messageData.chatUser.avatar] || null
    }
  },
  mounted() {
    this.updateDateTime()
  },
  beforeDestroy() {
    if (this.dateTimeRefreshTimeout) {
      clearTimeout(this.dateTimeRefreshTimeout)
    }
  },
  methods: {
    randomInt() {
      const min = 5000
      const max = 10000
      return Math.floor(Math.random() * (max - min + 1)) + min
    },
    updateDateTime() {
      if (this.dateTimeRefreshTimeout) {
        clearTimeout(this.dateTimeRefreshTimeout)
      }
      this.dateTime = moment(this.messageData.created).fromNow()
      this.dateTimeRefreshTimeout = setTimeout(() => {
        this.updateDateTime()
      }, this.randomInt())
    }
  }
}
</script>

<style lang="sass">
@import "assets/sass/utilities"
.chat-message-row
  padding: 1.5rem 1rem
  border: solid $grey-lighter
  border-width: 1px 0 0
  &:first-child
    border-width: 0
  .chat-message
    font-size: 1.1rem
    .avatar
      width: 65px
      +mobile
        width: 30px
    .username
      font-size: 1.2rem
      color: $blue
</style>
