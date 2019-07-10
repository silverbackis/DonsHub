<template>
  <div class="terraces">
    <boarding />
    <div class="crowd">
      <div v-for="x in 9" :key="'terrace-' + x" class="terrace" />
    </div>
    <div class="coming-soon box">
      <h1 class="title">Coming Soon</h1>
      <p>
        We are working on this feature so you will soon be able to see your
        avatar in the terraces.
      </p>
    </div>
    <div class="attendance-bar has-background-primary has-text-white">
      <div class="container">Attendance: {{ users ? users.length : '--' }}</div>
    </div>
  </div>
</template>

<script>
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
    usersData() {
      return this.users.map(user => this.getEntity(user))
    }
  }
}
</script>

<style lang="sass">
@import "assets/sass/utilities"
.terraces
  position: relative
  background: $grey
  .boarding,
  .crowd
    opacity: .4
  +mobile
    border-top: 10px solid $gold
  .coming-soon
    text-align: center
    position: absolute
    top: 50%
    left: 50%
    transform: translate3d(-50%, -60%, 0)
    padding: 2rem
    background: rgba($white, .95)
    max-width: 500px
    width: 90%
    +mobile
      padding: 1.4rem
      .title
        font-size: 1.4rem
      p
        font-size: .9rem
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
