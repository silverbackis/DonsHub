<template>
  <div class="terraces">
    <boarding />
    <div class="crowd">
      <div v-for="x in 7" :key="'terrace-' + x" class="terrace">
        <transition-group name="fan" tag="div" class="container terrace-middle">
          <div
            v-for="user in usersByRow[x]"
            :key="user['@id']"
            :style="{ left: getLeftPosition(user) }"
            class="avatar"
          >
            <img
              :src="'/avatars/' + avatars[user.avatar].image"
              :alt="avatars[user.avatar].description"
            />
          </div>
        </transition-group>
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
  },
  methods: {
    getLeftPosition(user) {
      const baseLeft = user.terraceSeat * 6
      // const finalLeft = baseLeft + user.offsetLeftPercent
      return baseLeft + '%'
    }
  }
}
</script>

<style lang="sass">
@import "assets/sass/utilities"
.terraces
  position: relative
  background: transparent
  padding-top: 50px
  margin-top: -50px
  overflow: auto
  .terrace-middle
    width: 70vw
    max-width: 600px
    position: relative
    height: 100%
    +mobile
      width: 100vw
    .avatar
      position: absolute
      bottom: 0
      width: 7%
      min-width: 32px
      > img
        width: 100%
  .terrace
    position: relative
    background: #E3DDCD
    height: 3vw
    min-height: 25px
    max-height: 35px
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
    z-index: 2
    position: relative

.fan-enter-active,
.fan-leave-active
  transition: opacity .3s, transform .3s

.fan-enter,
.fan-leave-to
  opacity: 0
  transform: translateY(50%)
</style>
