<template>
  <div class="league-table-row">
    <div class="container">
      <div class="columns is-mobile is-fullwidth">
        <div class="column is-1">
          {{ teamData.overallLeaguePosition }}
        </div>
        <div
          class="column"
          :class="{
            'has-text-weight-bold': teamData.teamId === clubTeamId
          }"
        >
          {{ teamData.teamName }}
        </div>
        <div class="column is-1 is-hidden-mobile has-text-centered">
          {{ teamData.overallGamesPlayed }}
        </div>
        <div class="column is-1 is-hidden-mobile has-text-centered">
          {{ teamData.overallGoalDifference }}
        </div>
        <div class="column is-1 has-text-centered">
          {{ teamData.overallPoints }}
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { mapState } from 'vuex'

export default {
  props: {
    team: {
      type: String,
      required: true
    }
  },
  computed: {
    ...mapState({
      clubTeamId: 'clubTeamId'
    }),
    teamData() {
      return this.getEntity(this.team)
    }
  }
}
</script>
