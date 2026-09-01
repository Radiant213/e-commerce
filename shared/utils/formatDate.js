/**
 * Format ISO datetime string to Indonesian readable format (e.g. 31 Agustus 2026, 18:30)
 */
export const formatDate = (dateString, withTime = false) => {
  if (!dateString) return '-';
  const date = new Date(dateString);
  if (isNaN(date.getTime())) return '-';

  const options = {
    day: 'numeric',
    month: 'long',
    year: 'numeric',
    ...(withTime ? { hour: '2-digit', minute: '2-digit' } : {}),
  };

  return new Intl.DateTimeFormat('id-ID', options).format(date);
};

export default formatDate;
