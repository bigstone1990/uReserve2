const nl2br = (str) => {
  str = str.replace(/\r\n/g, "<br />");
  str = str.replace(/(\n|\r)/g, "<br />");
  return str;
}

const customDateToString = (date) => {
  let year = date.getFullYear();
  let month = String(date.getMonth() + 1).padStart(2, '0');
  let day = String(date.getDate()).padStart(2, '0');
  let hour = String(date.getHours()).padStart(2, '0');
  let minute = String(date.getMinutes()).padStart(2, '0');
  let second = String(date.getSeconds()).padStart(2, '0');
  
  return `${year}-${month}-${day}`;
}

const customDateTimeToString = (date) => {
  let year = date.getFullYear();
  let month = String(date.getMonth() + 1).padStart(2, '0');
  let day = String(date.getDate()).padStart(2, '0');
  let hour = String(date.getHours()).padStart(2, '0');
  let minute = String(date.getMinutes()).padStart(2, '0');
  let second = String(date.getSeconds()).padStart(2, '0');

  return `${year}-${month}-${day} ${hour}:${minute}:${second}`
}

const isEmpty = (obj) => {
  return Object.keys(obj).length === 0
}

// 関数を別ファイルで使い回すための記述
export { nl2br, customDateToString, customDateTimeToString, isEmpty }