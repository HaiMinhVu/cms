import {
  replace,
  startCase
} from 'lodash';

export function formatTitle(str, pattern = '_') {
  return startCase(replace(str, pattern, ' '));
}
