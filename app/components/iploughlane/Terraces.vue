<template>
  <div class="terraces">
    <boarding />
    <div class="crowd">
      <div v-for="x in 9" :key="'terrace-' + x" class="terrace">
        <div class="container terrace-middle">
          <div
            v-for="user in usersByRow[x]"
            :key="user['@id']"
            :style="{ left: user.terraceSeat * 5 + '%' }"
            class="avatar"
          >
            <img
              :src="'/avatars/' + avatars[user.avatar].image"
              :alt="avatars[user.avatar].description"
            />
          </div>
        </div>
      </div>
    </div>
    <div class="attendance-bar has-background-primary has-text-white">
      <div class="container">Attendance: {{ users ? users.length : '--' }}</div>
    </div>
  </div>
</template>

<script>
import { mapState } from 'vuex'
import Boarding from './Boarding'

export default {
  components: {
    Boarding
  },
  props: {
    users: {
      type: Array,
      required: false,
      default: null
    }
  },
  computed: {
    ...mapState({
      avatars: 'avatars'
    }),
    usersData() {
      return this.users ? this.users.map(user => this.getEntity(user)) : []
    },
    usersByRow() {
      const rows = {}
      for (const user of this.usersData) {
        if (!rows[user.terraceRow]) {
          rows[user.terraceRow] = []
        }
        rows[user.terraceRow].push(user)
      }
      return rows
    }
  }
}
</script>

<style lang="sass">
@import "assets/sass/utilities"
.terraces
  position: relative
  background: $grey
  +mobile
    border-top: 10px solid $gold
  .terrace-middle
    max-width: 800px
    position: relative
    height: 100%
    +mobile
      max-width: 100%
    .avatar
      position: absolute
      bottom: 0
      width: 6%
      min-width: 32px
      > img
        width: 100%
  .terrace
    position: relative
    background: #E3DDCD
    height: 30px
    +mobile
      height: 25px
    &:before,
    &:after
      content: ''
      position: absolute
      left: 0
      width: 100%
      height: 20%
    &:before
      top: 0
      background-color: #7a7361
    &:after
      bottom: 0
      background-color: #afa58c
  .attendance-bar
    padding: .5rem
    font-size: 1.2rem
</style>
