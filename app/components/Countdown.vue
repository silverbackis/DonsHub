<template>
  <div>
    <p v-if="gatesOpen" class="has-text-primary has-text-weight-bold is-size-4">
      <span v-if="match.matchStatus === 'Finished'">
        Match Finished
      </span>
      <span v-else-if="match.matchStatus === ''">
        Gates Open
      </span>
      <span v-else>Game Minute '{{ match.matchStatus }} </span>
    </p>
    <template v-else>
      <p>{{ gatesOpen ? 'Match starts' : 'Gates open' }} in</p>
      <p v-if="difference" class="is-size-4 is-size-5-mobile">
        <template v-if="ms <= 0">
          --
        </template>
        <template v-else>
          <span v-if="difference.days">{{ difference.days }}d&nbsp;</span>
          <span v-if="difference.hours">{{ difference.hours }}h&nbsp;</span>
          {{ difference.minutes }}m&nbsp;{{ difference.seconds }}s
        </template>
      </p>
    </template>
  </div>
</template>

<script>
import CountdownMixin from './CountdownMixin'
export default {
  mixins: [CountdownMixin]
}
</script>
