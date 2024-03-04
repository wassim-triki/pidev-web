import React from 'react';
import ReactDOM from 'react-dom';
import AddComment from './components/Addcomment';
import EditComment from './components/EditComment';
import DeleteComment from './components/DeleteComment';

ReactDOM.render(
  <React.StrictMode>
    <AddComment />
    <EditComment />
    <DeleteComment />
  </React.StrictMode>,
  document.getElementById('comment-app')
);
